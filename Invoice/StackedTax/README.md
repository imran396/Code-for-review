# Stacked Tax Invoicing

#stacked-tax #invoice #invoice-generation #calculate #tax-schema #tax-definition

This namespace contains domain logic related to invoicing generated with the Stacked Tax feature consideration.  
Copy here logic of `\Sam\Invoice` modules and adopt it fot the Stacked Tax invoicing.  
Try to do not re-use `\Sam\Invoice` logic, because there can be differences in new implementation.  
CC Charging logic `\Sam\Invoice\Charge` can be re-used. Possibly some more, still in research (2022-08).  
Place impure logic in this `\Sam\Invoice\StackedTax` namespace and pure logic in `\Sam\Core\Invoice\StackedTax`
namespace.  
When the scope of logic in `\Sam\Invoice\StackedTax` will be clear, then we will be able to separate logic
of the Legacy Invoicing from common logic of invoice domain.  
It could be the next namespace separation: `\Sam\Invoice\Legacy`, `\Sam\Invoice\StackedTax`, `\Sam\Invoice\Common`.

## Tickets

* SAM-11061: Stacked Tax. Invoice Management pages. Implement Stacked Tax Invoice Generation logic
