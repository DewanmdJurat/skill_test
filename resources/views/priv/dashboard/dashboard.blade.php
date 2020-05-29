@extends('home')

@section('privContent')
<header class="page-header">
    <h2>Home</h2>

    <div class="right-wrapper text-right mr-2">
        <ol class="breadcrumbs">
            <li>
                <a href="{{ route('home') }}">
                    <i class="fas fa-home"></i>
                </a>
            </li>
        </ol>
    </div>
</header>
@endsection