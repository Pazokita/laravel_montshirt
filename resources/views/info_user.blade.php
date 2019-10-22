@auth
    <span class="badge badge-primary">Bienvenue {{Auth::user()->name}}</span>
    <a class="badge badge-primary" href="{{ route('logout') }}"
       onclick="event.preventDefault();
     document.getElementById('logout-form').submit();"><i style="color:white" class="fa fa-sign-out-alt"></i>
    </a>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
@endauth
