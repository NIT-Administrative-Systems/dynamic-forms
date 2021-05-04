# Extending
There are several ways you can extend Dynamic Forms.

## Adjusting Global Settings
Dynamic Forms modifies the default settings for most components to disable unsupported features and hides a lot of technical options that end-users would not be able to use.

These customizations are achieved by hijacking the `Formio.builder()` & `Formio.createForm()` methods, applying the Dynamic Forms defaults, and then applying the `options` parameter.

All of the defaults are set in `resources/js/formio/defaults.js`. There are two important parts of this file: 

1. The `global` key contains options that are applied to the builder config screens for every component
1. The `specificFields` key contains options applied to one type of component

You can adjust these settings, at your own risk. Some options are disabled because Dynamic Forms does not support validating them server-side.

## Custom Components
There are two ways you can add components to the frontend: 

1. Customizing existing components for the form builder, so they are presented as new components to users
1. Writing your own component from scratch & registering it with Formiojs

### Customizing Existing Components
Customizing an existing component can be something like giving your form builder users a Select component with a standard set of options used across your whole application. This component can have standard validation rules, labeling, and help text -- the developer is in control of how much or how little a user can configure it.

All of the work involved in making this custom component happens in JavaScript. Your Laravel application is not aware that a form is using one of these components. To continue the example above: when a user fills out that form and submits it, Dynamic Forms just sees a typical Select component with values and validation rules.  

Examples of this approach can be found in the [Formiojs documentation's examples section](https://formio.github.io/formio.js/app/examples/custombuilder.html).

### Writing Components from Scratch
Writing a component from scratch gives you complete control over your component's UI, builder config options, and behaviours. 

There are two aspects to the custom component: the JavaScript to add your component to Formiojs, and a component in your Laravel application that tells Dynamic Forms how to validate submissions.

For examples of custom Formiojs components, see the [formio/contrib repository](https://github.com/formio/contrib/tree/master/src/components), or look to [the Formiojs components](https://github.com/formio/formio.js/tree/master/src/components) for examples.

### Registering with Formiojs
You need to tell Formiojs about your component before it will show up in the builder.

To do this, edit your `resources/js/formio/index.js` file. You need to import the component & edit form, then locate the comment that says '*If you want to load custom code*' and perform the registration.

```js
// . . .
import DirectorySearch from "../directory-search";
import DirectoryEditForm from "../directory-search/form";

// . . .

// -------------------------------------------------------------------------
// If you want to load custom code (like additional components), do it here!
// -------------------------------------------------------------------------
Formio.use(DirectorySearch);
Formio.Components.components.directorySearch.editForm = DirectoryEditForm;

// . . .
```

#### Registering Server-side
Dynamic Form comes with support for most of the components that Formiojs supports.

If you want to implement a missing component in the server-side validations, or if you've written your own Formiojs component, you need to register it with the component registry. The best place to register components is in a service provider's boot method.

Here is an example of creating and registering a layout component for tabs.

```php
// app\Tabs.php

namespace App;

use Northwestern\SysDev\DynamicForms\Components\ComponentInterface;
use Northwestern\SysDev\DynamicForms\Components\BaseComponent

// You can extend BaseComponent for a lot of functionality, or implement 
// the ComponentInterface if you need to go totally outside the box.
class Tabs extends BaseComponent
{
    const TYPE = 'tabs';
    
    public function canValidate(): bool
    {
        return false;
    }
}
```

```php
// app\Providers\AppServiceProvider.php

namespace App\Providers;

use App\Tabs;
use Illuminate\Support\ServiceProvider;
use Northwestern\SysDev\DynamicForms\ComponentRegistry;

class AppServiceProvider extends ServiceProvider
{
    // . . .
    
    public function boot()
    {
        // . . .
        
        /** @var ComponentRegistry $registry */
        $registry = $this->app->make(ComponentRegistry::class);

        $registry->register(Tabs::class);
    }
}
```
