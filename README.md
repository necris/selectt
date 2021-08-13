## Selectt
Simple implementation of Select2 for Nette Framework.

How to use:

1. register extension in config:

```
extensions:
    selectt: Selectt\DI\SelecttExtension
```

2. add some configuration

```
selectt:
  single:
    class: select2-ajax
    jsAttributes:
      allowClear: true
      placeholder: Please select option
      width: 100%
  multi:
    class: select2-ajax-multi
    jsAttributes:
      allowClear: true
      placeholder: Please select
      width: 100%
```
Section `jsAttributes` contains any Slelect2 parameter you like. If you want to use that parameter only on one select, you can also use metho `->addSelect2Attribute($name, $value)` on corresponding object.


3. include Select2 to your project
4. init Select2 for desired class

```
$(function(){
    $('.select2-ajax').each(function () {
        let params = $(this).data().select2Params;
        params.ajax = {
            url: $(this).data('select2-url'),
            dataType: 'json'
        };

        $(this).select2(params);
    });

    $('.select2-ajax-multi').each(function () {
        let params = $(this).data().select2Params;
        params.ajax = {
            url: $(this).data('select2-url'),
            dataType: 'json'
        };

        $(this).select2(params);
    });
});
```

5. create ajax autocomplete component with your own implementation of `Selectt\SelecttDataSource`
```
public function createComponentAutocomplete(): SelecttAutocompleteControl
{
  $a = new SelecttAutocompleteControl(new ArrayDataSource([
      1 => "Praha",
      2 => "Brno",
      3 => "Paříž",
      4 => "Hitler",
      5 => "patří",
      6 => "za",
      7 => "mříž"
  ]));
  $a->enableEmptyQuery();
  return $a;
}
```

6. finaly add select2 or multiselect to form. As second parameter provide autocomplete component!
```
public function createComponentForm(): Nette\Application\UI\Form
{
    $f = new Nette\Application\UI\Form();
    $f->addText("text", "text");
    $f->addSelect2("select2", $this['autocomplete'], "Best select in the world");
    $f->addSelect2multi("select2multi", $this['autocomplete'], "Best multiselect ever");
    .
    .
    .
    return $f;
}
```

7. profit!