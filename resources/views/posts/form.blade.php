<div class="form-group">
    <label for="name">Title</label>
    {!! Form::text('title', null, [
        'class'=>'form-control',
    ]) !!}
    <br>
    <label for="image">Image</label>
    {!! Form::file('image', null, [
        'class'=>'form-control',
    ]) !!}
    <br>
    <label for="name">Body</label>
    {!! Form::text('body', null, [
        'class'=>'form-control',
    ]) !!}
    <br>
    {!! Form::select('category_id', $categories->pluck('title', 'id'),null,[
        'class'=>'form-control',
    ]) !!}

</div>

<div class="form-group">
    <button class="btn btn-primary" type="submit">Submit</button>
</div>

{!! Form::close() !!}