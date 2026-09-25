@auth
    {{-- Logged in: account management replaces Registration/Login links --}}
    <li><a href="{{ route('account') }}" class="hover:text-primary">Account management</a></li>
    <li>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="hover:text-primary">Logout</button>
        </form>
    </li>
@else
    <li><a href="{{ route('register') }}" class="hover:text-primary">Registration</a></li>
    <li><a href="{{ route('login') }}" class="hover:text-primary">Login</a></li>
@endauth
