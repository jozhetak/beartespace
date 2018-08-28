<div class="app-header--middle">

    <div class="app-header--left">

        @if(Route::currentRouteName() === 'home')
            <div class="app-header-invites">
                <a href="{{ route('invite.artist') }}">For Artists</a>| &nbsp;
                <a href="{{ route('invite.gallery') }}">For Galleries</a>| &nbsp;
                <a href="{{ route('invite.writer') }}">For Art Writers</a>
            </div>
        @elseif(Request::segment(1) === 'dashboard' || Request::segment(1) === 'login' || Request::segment(1) === 'register')
            <div class="app-header-logo">
                <a href="{{ route('home') }}">
                    <img src="/imagecache/height-40/logo.png" alt="BeArteSpace logo"/>
                </a>
            </div>

        @endif

    </div>


    <div class="app-header-currencies">
        <el-dropdown trigger="click">
                      <span class="el-dropdown-link">
                          {{ getCurrentCurrency() }}
                          <i class="el-icon-arrow-down el-icon--right"></i>
                      </span>
            <el-dropdown-menu slot="dropdown">
                @foreach(currency()->getCurrencies() as $currency)
                    @if($currency['code'] !== getCurrentCurrency())
                        <el-dropdown-item>
                            <a href="{{ route('switch-currency', $currency['code']) }}">{{ $currency['code'] }}</a>
                        </el-dropdown-item>
                    @endif
                @endforeach
            </el-dropdown-menu>
        </el-dropdown>
    </div>

    <div class="app-header-languages">
        <el-dropdown trigger="click">
                      <span class="el-dropdown-link">
                          {{ currentLanguage()->name ?? 'en' }}
                          <i class="el-icon-arrow-down el-icon--right"></i>
                      </span>
            <el-dropdown-menu slot="dropdown">
                @foreach(getLanguages() as $lang)
                    @if($lang->code !== app()->getLocale())
                        <el-dropdown-item>
                            <a href="{{ route('switch-language', $lang->code) }}">{{ $lang->name }}</a>
                        </el-dropdown-item>
                    @endif
                @endforeach
            </el-dropdown-menu>
        </el-dropdown>
    </div>


    <div class="app-header-auth">
        @if (Auth::guest())
            <a href="{{ route('login') }}">@lang('portal.login')</a>&nbsp; | &nbsp;
            <signup-dialog type_="link"></signup-dialog>
        @else
            <el-dropdown trigger="click">
                      <span class="app-header-auth-name">
                              <span class="app-header-auth-avatar">
                                  <img src="/imagecache/mini-avatar/{{ auth()->user()->avatar_url}}"/>
                              </span>
                          {{ auth()->user()->name }}

                          <i class="el-icon-arrow-down el-icon--right"></i>
                      </span>
                <el-dropdown-menu slot="dropdown">

                    <el-dropdown-item>
                        <a href="{{route('dashboard')}}" class="el-dropdown-link">Dashboard</a>
                    </el-dropdown-item>

                    <el-dropdown-item>
                        <a href="{{ route('dashboard.payments') }}" class="el-dropdown-link">Payments</a>
                    </el-dropdown-item>


                    <el-dropdown-item>
                        <a href="{{route('dashboard.profile')}}" class="el-dropdown-link">Profile</a>
                    </el-dropdown-item>

                    <el-dropdown-item>
                        <a href="{{route('dashboard.favorites')}}" class="el-dropdown-link">Favorites</a>
                    </el-dropdown-item>

                    @if(!auth()->user()->isUser())

                        <el-dropdown-item>
                            <a href="{{route('dashboard.artworks')}}" class="el-dropdown-link">Artworks</a>
                        </el-dropdown-item>

                        <el-dropdown-item>
                            <a href="{{route('dashboard.artwork.create')}}" class="el-dropdown-link">Upload
                                Artwork</a>
                        </el-dropdown-item>

                    @endif

                    @if(auth()->user()->isAdmin())

                        <el-dropdown-item>
                            <a href="{{route('admin.messages')}}" class="el-dropdown-link"><i
                                        class="el-icon-message"></i> Messages</a>
                        </el-dropdown-item>

                        <el-dropdown-item>
                            <a href="{{ route('admin.users') }}"><i class="icon-user-outline"></i> Users</a>
                        </el-dropdown-item>

                        <el-dropdown-item>
                            <a href="{{ route('admin.translations') }}"><i class="el-icon-tickets"></i>
                                Translations</a>
                        </el-dropdown-item>

                        <el-dropdown-item>
                            <a href="{{ route('admin.languages') }}"><i class="el-icon-setting"></i>
                                Languages</a>
                        </el-dropdown-item>

                        <el-dropdown-item>
                            <a href="{{ route('admin.settings') }}"><i class="el-icon-setting"></i> Settings</a>
                        </el-dropdown-item>

                        <el-dropdown-item>
                            <a href="{{ route('admin.pages') }}"><i class="el-icon-document"></i> Pages</a>
                        </el-dropdown-item>

                    @endif

                    <el-dropdown-item>
                        <a href="{{route('dashboard.change-password')}}" class="el-dropdown-link">Change
                            Password</a>
                    </el-dropdown-item>

                    <el-dropdown-item>
                        <a href="{{ route('logout') }}"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                           class="el-dropdown-link">
                            Logout
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST"
                              style="display: none;">
                            {{ csrf_field() }}
                        </form>
                    </el-dropdown-item>

                </el-dropdown-menu>
            </el-dropdown>
        @endif
    </div>

    @if(!auth()->user() ? !Cookie::get('email_lead_subscription') : null)
        &nbsp; |
        <div class="app-header-subscribe">
            <el-popover
                    placement="bottom"
                    width="400"
                    trigger="click">
                <el-form inline label-position="top" method="POST" action="{{ route('add-lead') }}">
                    {{ csrf_field() }}
                    <el-input name="email" type="email" placeholder="Sign up to our email news"
                              required>
                        <el-button slot="append" native-type="submit" type="primary">Join</el-button>
                    </el-input>
                </el-form>
                <div slot="reference">Subscribe for Newsletters</div>

            </el-popover>
        </div>
    @endif

    <a href="{{ route('dashboard.favorites') }}" class="app-header-star"
       v-if="$store.state.favouritesCount">
        <i class="el-icon-star-off"></i><sup>@{{ $store.state.favouritesCount }}</sup>
    </a>

    <a href="{{ route('shopping-cart') }}" class="app-header-cart" v-if="$store.state.cartCount">
        <i class="el-icon-goods"></i><sup>@{{ $store.state.cartCount }}</sup>
    </a>

    @if(auth()->user() && auth()->user()->user_type === 'artist')
        <el-button type="success" size="mini" style="margin-left:10px;"><a
                    href="{{ route('dashboard.artwork.create') }}">
                Upload Artwork
            </a></el-button>
    @endif

</div>