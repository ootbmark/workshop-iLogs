<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {!! SEO::generate() !!}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    
    @yield('add-css')

    @include('layouts._head_scripts')
    @yield('head')
    <script>
        window.trans = <?php
        // copy all translations from /resources/lang/CURRENT_LOCALE/* to global JS variable
        $lang_files = File::files(resource_path() . '/lang/' . App::getLocale());
        $trans = [];
        foreach ($lang_files as $f) {
            $filename = pathinfo($f)['filename'];
            if (pathinfo($f)['filename'] == 'lesson' || pathinfo($f)['filename'] == 'course' || pathinfo($f)['filename'] == 'quiz') {
                $trans[$filename] = trans($filename);
            }
        }
        echo json_encode($trans);
        
        ?>;
    </script>
</head>

<body>

    <nav class="header navbar-expand-lg" id="header-1">
        <div class="header-container d-flex justify-content-between align-items-center">
            <a href="{{ url('/') }}" class="logo">
                <img src="{{ asset('/img/logo.png') }}" alt="my spread" width="99">
            </a>

            <div class="d-lg-none position-relative ml-auto mr-3" style="">
                <div class="search-button ml-4">
                    <a href="#" class="search-toggle" data-selector="#header-1" aria-label="search"></a>
                </div>
                <form action="{{ route('discussions.index') }}" class="search-box">
                    <input type="text" class="text search-input" name="search" placeholder="Search..."
                        aria-label="search" />
                </form>
            </div>

            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContentd" aria-expanded="false"
                aria-label="{{ __('Toggle navigation') }}">
                <span id="nav-icon3" class="open"><span></span><span></span><span></span><span></span></span>
            </button>

            <div class="justify-content-end collapse navbar-collapse position-relative" id="navbarSupportedContent">

                <ul class="list-unstyled d-flex w-100 justify-content-end header-nav mb-0 align-items-center">
                    <li class="menu-list-item dropdown">
                        <a class="dropdown-toggle" href="#" role="button" data-toggle="dropdown">About</a>
                        <ul class="list-unstyled sub-menu dropdown-menu">
                            <li><a href="{{ route('about') }}">About Spread</a></li>
                            <li><a href="{{ route('organisations.index') }}">Member organisations</a></li>
                            <li><a href="{{ route('guide') }}">User guide</a></li>
                            <li><a href="{{ route('useful') }}">Useful links</a></li>
                        </ul>
                    </li>
                    <li class="menu-list-item dropdown">
                        <a class="dropdown-toggle" href="#" role="button" data-toggle="dropdown">Discussions</a>
                        <ul class="list-unstyled sub-menu dropdown-menu">
                            <li><a href="{{ route('groups.index') }}">View all groups</a></li>
                            <li><a href="{{ route('discussions.index') }}">View all discussions</a></li>
                        </ul>
                    </li>
                    <li class="menu-list-item"><a href="{{ route('forms.index.main') }}">Workshop Outputs</a></li>
                    <li class="menu-list-item"><a href="#" data-toggle="modal"
                            data-target="#privateForumModal">Private Forums</a></li>
                    <li class="menu-list-item"><a href="{{ route('contact') }}">Contact</a></li>
                    <li class="menu-list-item"><a href="{{ route('help') }}">Help</a></li>
                    @guest
                        <li class="menu-list-item"><a href="{{ route('login') }}">Login / Register</a></li>
                    @else
                        <li class="menu-list-item dropdown">
                            <a id="navbarDropdown" class="dropdown-toggle" href="#" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                <img src="{{ asset('/img/user.svg') }}" class="" alt="user" width="30">
                            </a>
                            <div class="list-unstyled sub-menu dropdown-menu dropdown-menu-right text-right sub-menu-profile"
                                aria-labelledby="navbarDropdown">

                                <a class="d-flex align-items-center" href="{{ route('profile.index') }}"><img
                                        src="{{ asset('/img/user-b.svg') }}" alt="user">{{ __('Profile') }}</a>
                                @if (auth()->user()->role == 'admin')
                                    <a class="d-flex align-items-center" href="{{ route('dashboard.users.index') }}"><img
                                            src="{{ asset('/img/id-card-b.svg') }}"
                                            alt="card">{{ __('Dashboard') }}</a>
                                @endif
                                <a class="d-flex align-items-center" href="{{ route('profile.invite') }}"><img
                                        src="{{ asset('/img/invite-b.svg') }}"
                                        alt="invite">{{ __('Invite friends') }}</a>
                                <a class="d-flex align-items-center" href="{{ route('profile.find') }}"><img
                                        src="{{ asset('/img/search-b.png') }}"
                                        alt="find">{{ __('Find friends') }}</a>
                                <a class="d-flex align-items-center" href="{{ route('profile.notifications') }}"><img
                                        src="{{ asset('/img/share-post-b.svg') }}"
                                        alt="share">{{ __('Notification') }}</a>
                                <a class="d-flex align-items-center" href="{{ route('profile.password') }}"><img
                                        src="{{ asset('/img/password-b.svg') }}" alt="password">{{ __('Password') }}</a>

                                <a class="d-flex align-items-center" href="{{ route('logout') }}"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><img
                                        src="{{ asset('/img/logout-b.svg') }}" alt="logout">{{ __('Logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    @endguest
                </ul>

                <div class="d-none d-lg-block">
                    <div class="search-button ml-4">
                        <a href="#" class="search-toggle" data-selector="#header-1" aria-label="search"></a>
                    </div>
                    <form action="{{ route('discussions.index') }}" class="search-box">
                        <input type="text" name="search" class="text search-input" placeholder="Search..."
                            aria-label="search" />
                    </form>
                </div>

            </div>
        </div>
    </nav>

    <main>
        @yield('content')
    </main>

    <footer class="mt-auto">
        <div class="footer-container text-center">
            <a href="/" class="mr-auto" aria-label="logo">
                <img src="{{ asset('/img/logo.png') }}" alt="myspread" width="100" loading="lazy">
            </a>
            <div class="footer-links">
                <a href="{{ route('privacy') }}">Privacy</a>
                <a href="{{ route('faq') }}">FAQs</a>
                <a href="{{ route('contact') }}">Feedback</a>
            </div>
            <a href="mailto:dave@rp-squared.com?Subject=Hello" class="footer-mail">
                <img src="{{ asset('/img/email.svg') }}" alt="email" width="20" loading="lazy">
                dave@rp-squared.com
            </a>

            <div class="footer-socials d-flex align-items-center justify-content-center">
                <a href="https://www.facebook.com/Myspread-1655862491362528/" rel="noreferrer" target="_blank"
                    title="facebook" aria-label="facebook">
                    <img src="{{ asset('/img/facebook.svg') }}" alt="facebook" width="18" loading="lazy">
                </a>
                <a href="https://twitter.com/myspread/" rel="noreferrer" target="_blank" title="twitter"
                    aria-label="twitter">
                    <img src="{{ asset('/img/twitter.svg') }}" alt="twitter" width="16" loading="lazy">
                </a>
                <a href="https://www.linkedin.com/company/my-spread" rel="noreferrer" target="_blank"
                    title="linkedin" aria-label="linkedin">
                    <img src="{{ asset('/img/linkedin.svg') }}" alt="linkedin" width="16" loading="lazy">
                </a>
            </div>

            <div class="d-flex justify-content-center align-items-center">
                <p class="mb-0 mr-4">Powered by</p>
                <a href="https://www.rp-squared.com" target="_blank"><img
                        src="{{ asset('/img/newLogoFooter.png') }}" alt="myspread" width="250" loading="lazy"
                        class="powered-img"></a>
            </div>
        </div>

        <div class="copyright">
            <div class="copyright-container text-center">
                <p class="mb-1 mr-3">{{ date('Y') }} &copy; SPREAD. All Rights Reserved. Designated trademarks and
                    brands are the property of their respective owners.</p>
                <a href="https://ootbinnovations.com/" target="_blank" rel="noopener">
                    <p>DEVELOPED BY OUT OF THE BOX INNOVATION</p>
                </a>
            </div>
        </div>

        <div class="third-arrow text-center">
            <a href="#" class="scroll-top btn" data-id="top"><i class="fa fa-angle-up"></i></a>
        </div>
    </footer>

    <div class="modal" tabindex="-1" id="privateForumModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center p-4">
                    <p>Private Forums are corporate spaces that allow groups to use the functionality of
                        MySpread for confidential projects. If you feel your company could benefit from a
                        private forum, please contact <a href="mailto:admin@my-spread.com">admin@my-spread.com</a></p>
                    <button type="button" class="btn my-btn mt-3" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ mix('js/app.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
   
    <script>
        $(".toggle-password").click(function() {

            $(this).toggleClass("eye-slash");
            var input = $($(this).attr("toggle"));
            if (input.attr("type") == "password") {
                input.attr("type", "text");
            } else {
                input.attr("type", "password");
            }
        });

        $(function() {
            $('[data-toggle="tooltip"]').tooltip()
        })

        $(document).ready(function() {
            $(".open_close-menu").click(function() {
                $('.my-profile-menu').toggleClass('profile-menu-opened');
            });
        });
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        });

        $('.j-back').click(function() {
            window.location.href = "{{ url()->previous() }}";
        });


        $(document).ready(function() {
            var current_url = window.location.href.split('?')[0];
            $.ajax({
                url: "{{ route('activity') }}",
                method: "POST",
                data: {
                    "url": current_url
                },
                success: function(data) {}
            });
        });

        $('form').submit(function() {
            $(this).find('.disabled-after-submit').attr('disabled', true);
        })




        // scroll function
        function scrollToID(id, speed) {
            var offSet = 0;
            var targetOffset = $(id).offset().top - offSet;
            $('html,body').animate({
                scrollTop: targetOffset
            }, speed);
        }

        $(document).ready(function() {
            // navigation click actions
            $('.scroll-link').on('click', function(event) {
                event.preventDefault();
                var sectionID = $(this).attr("data-id");
                scrollToID('#' + sectionID, 750);
            });
            // scroll to top action
            $('.scroll-top').on('click', function(event) {
                event.preventDefault();
                $('html, body').animate({
                    scrollTop: 0
                }, 'slow');
            });
        });
    </script>

    @include('layouts._foot_scripts')
    @yield('scripts')
    @include('flash::message')
</body>

</html>
