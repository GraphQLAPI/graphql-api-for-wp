This module allows to have all types and interfaces in the schema be automatically namespaced, by prepending their names with the corresponding PHP package's owner and name.

Namespacing the schema avoids naming conflicts, which happens when different owners (eg: different teams in the company, or 3rd party plugins) use the same name for a type or interface.

## Results

While the schema normally looks like this:

<a href="../../images/normal-interactive-schema.jpg" target="_blank">![Interactive schema](../../images/normal-interactive-schema.jpg)</a>

...its namespaced version looks like this:

<a href="../../images/namespaced-interactive-schema.jpg" target="_blank">![Namespaced interactive schema](../../images/namespaced-interactive-schema.jpg)</a>

In the namespaced schema, type `Post` becomes `PoP_ComponentModel_Post` and interface `CustomPost` becomes `PoP_ComponentModel_CustomPost` (following that the PHP package where this functionality was implemented has owner `"PoP"` and name `"ComponentModel"`).

## When to use

If plugins WooCommerce and Easy Digital Downloads both implemented a `Product` type for the GraphQL API, then we could not normally install both plugins at the same time. By enabling the Schema namespacing module, this conflict is avoided because their type names would be converted into `Automattic_WooCommerce_Product` and `SandhillsDevelopment_EasyDigitalDownloads_Product` respectively.

Similarly, the Marketing and Tutorials teams from the same company can release their own type `Discount`, instead of `Marketing_Discount` and `Tutorial_Discount`.
