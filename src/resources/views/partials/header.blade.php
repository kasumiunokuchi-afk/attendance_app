<div class="header__auth">
    @auth
        @if(auth()->user()->isAdmin())
            <a href="/admin/attendance/list" class="header__link">勤怠一覧</a>
            <a href="/admin/staff/list" class="header__link">スタッフ一覧</a>
            <a href="/stamp_correction_request/list" class="header__link">申請一覧</a>
        @else
            <a href="/attendance" class="header__link">勤怠</a>
            <a href="/attendance/list" class="header__link">勤怠一覧</a>
            <a href="/stamp_correction_request/list" class="header__link">申請</a>
        @endif
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="header__link">ログアウト</button>
        </form>
    @endauth
</div>