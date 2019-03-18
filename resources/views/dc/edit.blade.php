@extends('layouts.app')

@section('title')
Edit DC - {{ $dc->name }}
@endsection

@section('content')
<div class="container">
    <br class="clear" />

    <div class="row">
        <div class="col-md-6">
            <h2 class="title">Edit a DC - <small>{{ $dc->name }}</small></h2>
        </div>
        <div class="col-md-6 text-right">
            <a href="{{ route('dc.index') }}" class="btn btn-link">&larr; All DCs</a>&nbsp;
            <a href="{{ route('dc.show', [$dc]) }}" class="btn btn-default"><i class="fa fa-cogs" aria-hidden="true"></i> &nbsp; Manage DC</a>
        </div>
    </div>

    <br>

    @if ($dc->license()['licensed_bandwidth'] === 0 || $dc->license()['expiration_date'] < \Carbon\Carbon::now())
        @component('components.licensewarn')
        @endcomponent
    @endif

    <div class="row">

        <div class="col-md-12">
            @if ($errors->any())
                <div class="alert alert-dismissable alert-danger">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <h4>Error validating input:</h4>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-info">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <strong>Success!</strong> {{ session('success') }}
                </div>
            @endif
        </div>

        <form method="POST" action="{{ route('dc.update', [$dc]) }}">
            @csrf
            @method('PUT')

            <div class="col-md-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">Basic Configuration</h4>
                    </div>
                    <div class="panel-body">
                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="control-label">DC Name: <small class="text-muted"> &nbsp; (must be unique)</small></label>
                            <input type="text" class="form-control" name="name" value="{{ old('name', $dc->name) }}" placeholder="Friendly name for DC (eg: MAN4)" required>
                        </div>
                        <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
                            <label for="description" class="control-label">DC Notes:</label>
                            <textarea name="description" class="form-control" rows="5" placeholder="Add any notes about this DC to display throughout the app">{{ old('description', $dc->description) }}</textarea>
                        </div>
                        <div class="form-group{{ $errors->has('active') ? ' has-error' : '' }}">
                            <label for="active" class="control-label">DC Enabled:</label>
                            <select name="active" class="form-control">
                                <option value="1" {{ old('active', $dc->active)=='1' ? ' selected' : '' }}>Yes</option>
                                <option value="0" {{ old('active', $dc->active)=='0' ? ' selected' : '' }}>No</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>


            <div class="col-md-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">FastNetMon API Configuration</h4>
                    </div>
                    <div class="panel-body">
                        <div class="form-group{{ $errors->has('api_url') ? ' has-error' : '' }}">
                            <label for="api_url" class="control-label">FNM API URL: <small class="text-muted"> &nbsp; (with protocol, port, and trailing slash)</small></label>
                            <input type="text" class="form-control" name="api_url" value="{{ old('api_url', $dc->api_url) }}" placeholder="FastNetMon API URL (eg: http://fnm.network.example.com:10007/)" required>
                        </div>
                        <div class="form-group{{ $errors->has('api_username') ? ' has-error' : '' }}">
                            <label for="api_username" class="control-label">FNM API Username:</label>
                            <input type="text" class="form-control" name="api_username" value="{{ old('api_username', $dc->api_username) }}" placeholder="FastNetMon API Username (eg: admin)" required>
                        </div>
                        <div class="form-group{{ $errors->has('api_password') ? ' has-error' : '' }}">
                            <label for="api_password" class="control-label">FNM API Password:</label>
                            <input type="password" class="form-control" name="api_password" placeholder="FastNetMon API Password (edit to change)">
                        </div>
                        <div class="form-group{{ $errors->has('api_password') ? ' has-error' : '' }}">
                            <label for="api_password_confirmation" class="control-label">Confirm FNM API Password:</label>
                            <input type="password" class="form-control" name="api_password_confirmation" placeholder="FastNetMon API Password (edit to change)">
                        </div>
                        <div class="form-group{{ $errors->has('allowed_ip') ? ' has-error' : '' }}">
                            <label for="allowed_ip" class="control-label">Allowed Webhook IP: <small class="text-muted"></label>
                            <input type="text" class="form-control" name="allowed_ip" value="{{ old('allowed_ip', $dc->allowed_ip) }}" placeholder="IPv4 that is allowed to send webhooks as this DC" required>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <button class="btn btn-info loading" type="submit">Update DC</button>
            </div>
        </form>


        <div class="col-md-6 text-right">
            @if (!$dc->active)
            <form action="{{ route('dc.destroy', [$dc]) }}" method="POST">
                @csrf
                @method('DELETE')
                <button class="btn btn-primary loading" type="submit"><i class="fa fa-trash"></i> &nbsp;Delete DC</button>
            </form>
            @else
            <p class="text-muted">The DC must be disabled before deleting.</p>
            @endif
        </div>

    </div>
</div>
@endsection
