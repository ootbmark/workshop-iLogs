<meta name="cf-2fa-verify" content="3pw46ex0hgi38404">
@if(config('app.env') == 'production')
    <!-- Facebook Pixel Code -->
    <script>
        !function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
            n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
            n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
            t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
            document,'script','https://connect.facebook.net/en_US/fbevents.js');

        fbq('init', '1003760133006151');
        fbq('track', "PageView");</script>
    <noscript><img height="1" width="1" style="display:none"
                   src="https://www.facebook.com/tr?id=1003760133006151&ev=PageView&noscript=1"
        /></noscript>
    <!-- End Facebook Pixel Code -->

    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

        ga('create', 'UA-79263131-1', 'auto');
        ga('send', 'pageview');

        (function () {
            var script = document.createElement('script');
            script.src = 'https://app.activechat.ai/script/4d3bc162-d4fa-4999-aa64-79c9ff6231e0';
            script.id = 'ACCW_EMBED';
            document.getElementsByTagName('head')[0].appendChild(script); })();
    </script>
@endif
