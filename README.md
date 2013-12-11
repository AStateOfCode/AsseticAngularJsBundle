AsseticAngularJsBundle
======================
Simple Assetic filter to feed the *$templateCache*.

# Installation
```shell
composer require asoc/assetic-angular-js-bundle
```

## Requirements
Any Symfony2 2.3+ application will do.

# Configuration
None at the moment :)

# Usage
Just include the Angular templates as any other javascript resource using the javascripts Twig helper and apply the *angular* filter to them.

```twig
{% javascripts filter="angular"
    '@BundleName/Resources/views/aTemplate.html.ng'
    '@BundleName/Resources/views/fooTemplate.html.ng'
    '@BundleName/Resources/views/moarTemplates/*.html.ng'
    %}
    <script type="text/javascript" src="{{ asset_url }}"></script>
{% endjavascripts %}
```

The resulting output will be something like this:

```javascript
angular.module("BundleName/aTemplate.html", []).run(["$templateCache", function($templateCache) {
  $templateCache.put("BundleName/aTemplate.html", "HTML here");
}]);
angular.module("BundleName/fooTemplate.html", []).run(["$templateCache", function($templateCache) {
  $templateCache.put("BundleName/fooTemplate.html", "HTML here");
}]);
angular.module("BundleName/moarTemplates/bar.html", []).run(["$templateCache", function($templateCache) {
  $templateCache.put("BundleName/moarTemplates/bar.html", "HTML here");
}]);
// ...
```

The **.ng** extension is just a convention and can be changed at will. Also, the removal of the **Resources/views/** part is just by the symfony2 convention which can be changed by implementing a custom template name formatter. Now, to use the template a dependency on the module name must be set and after that the template can be retrieved using the templates URL:

```html
<div data-ng-include="BundleName/moarTemplates/bar.html"></div>
```

Of course, wherever a template URL can be specified, the above will work as it is in the default AngularJS template cache.

# License
MIT