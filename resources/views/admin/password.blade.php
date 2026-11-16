@extends('layouts.admin')
@section('title', 'Change Password')
@section('content')
<form class="box" method="post" action="{{ route('admin.password.save') }}" style="max-width:420px">
    @csrf
    <h2>Change Password</h2>
    <label>Current password</label><input type="password" name="current_password" required autocomplete="current-password">
    <label>New password (12+ characters, letters and numbers)</label><input type="password" name="password" required autocomplete="new-password">
    <label>Repeat new password</label><input type="password" name="password_confirmation" required autocomplete="new-password">
    <p><button class="btn">Update password</button></p>
</form>
@endsection
