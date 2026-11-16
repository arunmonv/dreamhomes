@extends('layouts.admin')
@section('title', 'Login')
@section('content')
<form class="box" method="post" action="{{ url('admin/login') }}" style="max-width:360px;margin:60px auto">
    @csrf
    <h2>Admin Login</h2>
    <label>Email</label><input type="email" name="email" value="{{ old('email') }}" required autofocus>
    <label>Password</label><input type="password" name="password" required>
    <label style="font-weight:400"><input type="checkbox" name="remember" value="1"> Remember me</label>
    <p><button class="btn">Login</button></p>
</form>
@endsection
