@extends('layouts.default')

@section('title')
    Home
@stop

@section('main')
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <h1>Permalinker</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <form id="url-form">
                    <div class="form-group">
                        <label for="url">URL</label>
                        <input class="form-control" type="url" name="url">
                    </div>
                    <button class="btn btn-primary btn-lg">Submit</button>
                </form>
            </div>
        </div>
    </div>
@stop

@section('scripts')
    @parent
    <script>
        $('#url-form').submit(function(e) {
            e.preventDefault();
            var form = this;

            var data = {
                '_token': '{{ csrf_token() }}',
                'url': $('input[name=url]').val()
            };

            $.post("{{ route('snapshot.store') }}", data, function(result) {
                window.location = result;
            });
        });
    </script>
@endsection
