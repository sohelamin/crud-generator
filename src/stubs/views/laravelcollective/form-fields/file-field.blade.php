@if(isset($%%crudNameSingular%%->%%itemName%%))
    <a href="{{ Storage::url('uploads/%%itemName%%/' . $%%crudNameSingular%%->%%itemName%%) }}">Existing %%itemName%%</a>
@endif
<input class="form-control" name="%%itemName%%" type="file" id="%%itemName%%" %%required%% />
{!! Form::file('%%itemName%%', null, ('%%required%%' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}