<?php

namespace App\Domains\User\NetID;

use App\Models\User;
use Illuminate\Support\Carbon;

/**
 * Takes DirectorySearch results and puts the fields onto their User model.
 */
class SyncUserFromDirectory
{
    /**
     * Updates a User model with appropriate directory data for their affiliations.
     *
     * This model HAS NOT been saved yet; the calling code will need to call save().
     */
    public function __invoke(User $user, array $directoryData): User
    {
        $user = $this->affiliations(
            $user,
            $directoryData['eduPersonPrimaryAffiliation'],
            $directoryData['eduPersonAffiliation'],
            $directoryData['nuAllSchoolAffiliations'],
        );

        $user = $this->demographics($user, $directoryData);

        $user->last_directory_sync_at = Carbon::now();

        return $user;
    }

    protected function affiliations(User $user, string $eduPersonPrimaryAff, array $eduPersonAff, array $nuSchoolAff): User
    {
        $user->is_emeritus = in_array(User::AFF_EMERITUS, $nuSchoolAff);
        $user->is_student = in_array(User::AFF_STUDENT, $eduPersonAff);
        $user->is_faculty = in_array(User::AFF_FACULTY, $eduPersonAff) || $user->is_emeritus;
        $user->is_staff = in_array(User::AFF_STAFF, $eduPersonAff);

        if ($eduPersonPrimaryAff !== 'employee') {
            $user->primary_affiliation = $eduPersonPrimaryAff;
        } else {
            // Emeritus may be primary affiliation = employee, which is meaningless, so convert that.
            if ($user->is_emeritus) {
                $user->primary_affiliation = User::AFF_FACULTY;
            } else {
                // Shouldn't happen. Will be quite exciting when it does.
                throw new \Exception(sprintf('Unknown primary affiliation %s for netID %s', $user->username, $eduPersonPrimaryAff));
            }
        }

        return $user;
    }

    protected function demographics(User $user, array $directoryData): User
    {
        $givenName = ['givenName'];
        $surname = ['sn'];
        $legalGiven = ['nuLegalGivenName', 'givenName'];
        $legalSur = ['nuLegalSn', 'sn'];
        $emplId = ['employeeNumber'];
        $email = ['mail'];
        $phone = ['telephoneNumber'];

        // Student data fields should have priority over less-specific fields when this is a student
        if ($user->primary_affiliation === User::AFF_STUDENT) {
            array_unshift($givenName, 'nuStudentGivenName');
            array_unshift($surname, 'nuStudentSn');
            array_unshift($legalGiven, 'nuStudentLegalGivenName');
            array_unshift($legalSur, 'nuStudentLegalSn');
            array_unshift($emplId, 'nuStudentNumber');
            array_unshift($email, 'nuStudentEmail');
            array_unshift($phone, 'nuAllStudentCurrentPhone');
        }

        $user->first_name = $this->findValue($directoryData, $givenName);
        $user->last_name = $this->findValue($directoryData, $surname);
        $user->legal_first_name = $this->findValue($directoryData, $legalGiven);
        $user->legal_last_name = $this->findValue($directoryData, $legalSur);
        $user->phone = $this->findValue($directoryData, $phone);
        $user->email = $this->findValue($directoryData, $email);
        $user->employee_id = $this->findValue($directoryData, $emplId);
        // $user->email_verified_at ??= Carbon::now();

        return $user;
    }

    /**
     * Check for the presence of multiple potential keys & return the first found.
     *
     * Some of the DirectorySearch results are ['a key'][0].
     * This helper pops the first array key out, if it looks like that.
     */
    private function findValue($data, $keys)
    {
        foreach ($keys as $key) {
            if ($data[$key] == null) {
                continue;
            }

            if (is_array($data[$key]) == true && count($data[$key]) > 0) {
                return $data[$key][0];
            }

            return $data[$key];
        }
    }
}
