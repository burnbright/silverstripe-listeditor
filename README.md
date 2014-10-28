# SilverStripe List Editor

A light-weight front-end control for adding, editing, and deleting DataObjects in a given DataList.

## Usage

Update your DataObject as follows:

 * Implement/extend the `getFrontEndFields` method to control form fields.
 * Implement/extend the `canCreate`, `canEdit`, and `canDelete` functions to control access. [see docs](http://doc.silverstripe.org/framework/en/reference/modeladmin#permissions).

Add the `ListEditForm` inside your controller class:
```php
<?php
function Form(){
    return new ListEditForm($this, 'Form', Member::currentUser()->Links());
}
?>
```


### Customising the template

You can make the editor template the same for a specific DataObject by adding a template named: `MyDataObject_ListEditField.ss` to your project.

You could also edit on a per-instance basis by using the ListEditField's `setTemplate` function.


## Troubleshooting

### Add/edit form redirects back without saving any changes

You may have fields added that are failing validation. All fields are required by default in the ListEditForm.
