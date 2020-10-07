@extends('admin.layouts.dashboard')
@section('content')


<style>
.container {
margin-top:2%;
}
</style>
</head>
<body>
@if (count($errors) > 0)
<div class="alert alert-danger">
<ul>
@foreach ($errors->all() as $error)
<li>{{ $error }}</li>
@endforeach
</ul>
</div>
@endif
<div class="container">
    <div class="row">
        <form action="/multiuploads" method="post" enctype="multipart/form-data">
        {{ csrf_field() }}
        <div class="col-md-12"><h2>Vendor Detail</h2>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                <label for="Vendor Name">Vendor Name</label>
                <input type="text" name="name" class="form-control"  placeholder="Vendor Name" >
                </div>
                <div class="form-group">
                <label for="Vendor PIcture">Vendor PIcture (only one):</label>
                <br />
                <input type="file" class="form-control" name="photos[]" multiple />
                </div>
                <div class="form-group">
                <label for="Vendor GST">Vendor GST (only one):</label>
                <br />
                <input type="file" class="form-control" name="photos[]" multiple />
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                <label for="Vendor Pan">Vendor Pan No. (only one):</label>
                <br />
                <input type="file" class="form-control" name="photos[]" multiple />
                </div>
                <div class="form-group">
                <label for="Vendor Name">Company cin </label>
                <input type="text" name="name" class="form-control"  placeholder="Vendor Name" >
                </div>
                <div class="form-group">
                <label for="Vendor Pan">Company Doc. (only one):</label>
                <input type="file" class="form-control" name="photos[]" multiple />
                </div>
            </div>
        <br />
        </div>
        <input type="submit" class="btn btn-primary" value="Upload" />
        </form>

    </div>
</div>


@endsection