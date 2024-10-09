<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>404 - Redmine</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Redmine" />
    <meta name="keywords" content="issue,bug,tracker" />
    <meta name="csrf-param" content="authenticity_token" />
    <meta name="csrf-token" content="D4VK+y89IBRsZ0GI7ltg2xdoZZsQdT3S2sqLoA2//TIUFZ0NS28DqWgD50sgS8nWYjAfCl0VrUGepVo35izJLw==" />
    <link rel='shortcut icon' href='/favicon.ico?1718153102' />
    <link rel="stylesheet" media="all" href="/stylesheets/jquery/jquery-ui-1.13.2.css?1718153102" />
    <link rel="stylesheet" media="all" href="/stylesheets/tribute-5.1.3.css?1718153102" />
    <link rel="stylesheet" media="all" href="/stylesheets/application.css?1718153102" />
    <link rel="stylesheet" media="all" href="/stylesheets/responsive.css?1718153102" />

    <script src="/javascripts/jquery-3.6.1-ui-1.13.2-ujs-6.1.7.6.js?1718153102"></script>
    <script src="/javascripts/tribute-5.1.3.min.js?1718153102"></script>
    <script src="/javascripts/tablesort-5.2.1.min.js?1718153102"></script>
    <script src="/javascripts/tablesort-5.2.1.number.min.js?1718153102"></script>
    <script src="/javascripts/application.js?1718153102"></script>
    <script src="/javascripts/responsive.js?1718153102"></script>
    <script>
        //<![CDATA[
        $(window).on('load', function(){ warnLeavingUnsaved('The current page contains unsaved text that will be lost if you leave this page.'); });
        //]]>
    </script>

    <script>
        //<![CDATA[
        rm = window.rm || {};rm.AutoComplete = rm.AutoComplete || {};rm.AutoComplete.dataSources = JSON.parse('{"issues":"/issues/auto_complete?q=","wiki_pages":"/wiki_pages/auto_complete?q="}');
        //]]>
    </script>

    <!-- page specific tags -->
</head>
<body class="has-main-menu controller-attachments action-download avatars-off">

<div id="wrapper">

    <div class="flyout-menu js-flyout-menu">

        <div class="flyout-menu__search">
            <form action="/search" accept-charset="UTF-8" name="form-db14a444" method="get"><input name="utf8" type="hidden" value="&#x2713;" autocomplete="off" />

                <label class="search-magnifier search-magnifier--flyout" for="flyout-search">&#9906;</label>
                <input type="text" name="q" id="flyout-search" class="small js-search-input" placeholder="Search" />
            </form>        </div>


        <h3>Project</h3>
        <span class="js-project-menu"></span>

        <h3>General</h3>
        <span class="js-general-menu"></span>

        <span class="js-sidebar flyout-menu__sidebar"></span>

        <h3>Profile</h3>
        <span class="js-profile-menu"></span>

    </div>


    <div id="top-menu">
        <div id="account">
            <ul><li><a class="login" href="/login">Sign in</a></li><li><a class="register" href="/account/register">Register</a></li></ul>    </div>

        <ul><li><a class="home" href="/">Home</a></li><li><a class="projects" href="/projects">Projects</a></li><li><a target="_blank" rel="noopener" class="help" href="https://www.redmine.org/guide">Help</a></li></ul></div>

    <div id="header">

        <a href="#" class="mobile-toggle-button js-flyout-menu-toggle-button"></a>

        <div id="quick-search">
            <form action="/search" accept-charset="UTF-8" name="form-f2fe3a9c" method="get"><input name="utf8" type="hidden" value="&#x2713;" autocomplete="off" />
                <input type="hidden" name="scope" autocomplete="off" />

                <label for='q'>
                    <a accesskey="4" href="/search">Search</a>:
                </label>
                <input type="text" name="q" id="q" size="20" class="small" accesskey="f" data-auto-complete="true" />
            </form>        <div id="project-jump" class="drdn"><span class="drdn-trigger">Jump to a project...</span><div class="drdn-content"><div class="quick-search"><input type="text" name="q" id="projects-quick-search" value="" class="autocomplete" data-automcomplete-url="/projects/autocomplete.js" autocomplete="off" /></div><div class="drdn-items projects selection"></div><div class="drdn-items all-projects selection"><a class="selected" href="/projects">All Projects</a></div></div></div>
        </div>

        <h1>Redmine</h1>

        <div id="main-menu" class="tabs">
            <ul><li><a class="projects" href="/projects">Projects</a></li><li><a class="activity" href="/activity">Activity</a></li><li><a class="issues" href="/issues">Issues</a></li><li><a class="news" href="/news">News</a></li></ul>
            <div class="tabs-buttons" style="display:none;">
                <button class="tab-left" onclick="moveTabLeft(this); return false;"></button>
                <button class="tab-right" onclick="moveTabRight(this); return false;"></button>
            </div>
        </div>
    </div>

    <div id="main" class="nosidebar">
        <div id="sidebar">


        </div>

        <div id="content">

            <h2>404</h2>

            <p id="errorExplanation">The page you were trying to access doesn&#39;t exist or has been removed.</p>


            <p><a href="javascript:history.back()">Back</a></p>



            <div style="clear:both;"></div>
        </div>
    </div>
    <div id="footer">
        Powered by <a target="_blank" rel="noopener" href="https://www.redmine.org/">Redmine</a> &copy; 2006-2024 Jean-Philippe Lang
    </div>

    <div id="ajax-indicator" style="display:none;"><span>Loading...</span></div>
    <div id="ajax-modal" style="display:none;"></div>

</div>

</body>
</html>