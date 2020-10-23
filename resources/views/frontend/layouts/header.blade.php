<nav id="header">
  <a href="/">
    <h2 class="text-light" style="padding: 0px 10px">Rent</h2>
  </a>
  @auth
    @if (Auth::user()->role==config('const.USER.ROLE.ADMIN'))
      <a href=""></a>    
    @endif
  @endauth
</nav>