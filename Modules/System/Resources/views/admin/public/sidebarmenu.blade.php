<style>
    .navigation li > .hidden-ul {
        display: none;
    }

    .navigation li.active > .hidden-ul {
        display: block;
    }
</style>
@php
    $currentUrl = url()->current();
    $currentFullUrl = request()->fullUrl();
    $currentPath = trim(request()->path(), '/');
    $sidebarMenuActives = [];
    $matchesSidebarMenu = function ($item) use (&$matchesSidebarMenu, $pageData, $currentUrl, $currentFullUrl, $currentPath) {
        $itemUrl = (string) ($item['url'] ?? '');
        $hasConcreteUrl = $itemUrl !== '' && $itemUrl !== '#';
        $itemFullUrl = $itemUrl !== '' && $itemUrl !== '#' ? url($itemUrl) : '';
        $itemPath = $itemFullUrl !== '' ? trim((string) parse_url($itemFullUrl, PHP_URL_PATH), '/') : '';
        $currentController = $pageData['controller'] ?? '';
        $currentAction = $pageData['action'] ?? '';
        $itemController = $item['controller'] ?? '';
        $itemAction = $item['action'] ?? '';
        $itemMatchActions = array_values(array_filter((array) ($item['match_actions'] ?? []), function ($action) {
            return is_string($action) && $action !== '';
        }));

        $selfMatched = false;
        if ($itemFullUrl !== '' && ($itemFullUrl === $currentFullUrl || $itemFullUrl === $currentUrl || $itemPath === $currentPath)) {
            $selfMatched = true;
        } elseif ($itemController !== '' && $itemController === $currentController && in_array($currentAction, $itemMatchActions, true)) {
            $selfMatched = true;
        } elseif ($hasConcreteUrl && $itemController !== '' && $itemController === $currentController) {
            $selfMatched = $itemAction !== '' && $itemAction !== '#' && $itemAction === $currentAction;
        } elseif (!$hasConcreteUrl && $itemController !== '' && $itemAction !== '' && $itemAction !== '#' && $itemController === $currentController && $itemAction === $currentAction) {
            $selfMatched = true;
        }

        if ($selfMatched) {
            return true;
        }

        foreach (($item['submenu'] ?? []) as $child) {
            if ($matchesSidebarMenu($child)) {
                return true;
            }
        }

        return false;
    };
    foreach (($menus ?? []) as $menuIndex => $menuItem) {
        $menuActive = $matchesSidebarMenu($menuItem);
        $sidebarMenuActives[$menuIndex] = $menuActive;
    }
@endphp
<div class="sidebar-category sidebar-category-visible">
    <div class="category-content no-padding">
        <ul class="navigation navigation-main navigation-accordion">
            @foreach($menus as $menuIndex => $menu)
                @php($menuActive = $sidebarMenuActives[$menuIndex] ?? false)
                @if($menu['url']!='#'&& $menu['action']!='#')
                    <li @if($menuActive) class="active" @endif>
                        <a href="{{url($menu['url'])}}" @if(!empty($menu['target'])) target="{{$menu['target']}}" @endif >
                            <i class="{{$menu['icon']}}"></i>
                            <span>{{ isset($menu['title_key']) ? getTranslateByKey($menu['title_key']) : $menu['title'] }}</span>
                        </a>
                    </li>
                @elseif($menu['url']=='#'&& $menu['action']=='#')
                    <li @if($menuActive) class="active" @endif >
                        <a href="#" class="has-ul">
                            <i class="{{$menu['icon']}}"></i>
                            <span>{{ isset($menu['title_key']) ? getTranslateByKey($menu['title_key']) : $menu['title'] }}</span>
                        </a>
                        <ul class="hidden-ul" @if(!$menuActive) style="display: none;" @endif>
                            @foreach(($menu['submenu'] ?? []) as $two)
                                @php($twoActive = $matchesSidebarMenu($two))
                                <li @if($twoActive) class="active" @endif>
                                    <a href="{{url($two['url'])}}" @if(!empty($two['target'])) target="{{$two['target']}}" @endif @if(!empty($two['submenu'])) class="has-ul" @endif>
                                        <i class="{{$two['icon']}}"></i>
                                        {{ isset($two['title_key']) ? getTranslateByKey($two['title_key']) : $two['title'] }}
                                    </a>

                                    @if(!empty($two['submenu']))
                                        <ul class="hidden-ul" @if(!$twoActive) style="display: none;" @endif>
                                            @foreach($two['submenu'] as $tree)
                                                @php($treeActive = $matchesSidebarMenu($tree))
                                                <li @if($treeActive) class="active" @endif>
                                                    <a href="{{url($tree['url'])}}" @if(!empty($tree['target'])) target="{{$tree['target']}}" @endif >
                                                        <i class="{{$tree['icon']}}"></i>
                                                        {{ isset($tree['title_key']) ? getTranslateByKey($tree['title_key']) : $tree['title'] }}
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </li>
                @endif
            @endforeach
        </ul>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var sidebarNav = document.querySelector('.sidebar-main .navigation-main.navigation-accordion');
        if (!sidebarNav) {
            return;
        }

        function menuSummary(item) {
            var anchor = item && item.querySelector(':scope > a');
            var childMenu = item && item.querySelector(':scope > ul');
            return {
                title: anchor ? anchor.textContent.replace(/\s+/g, ' ').trim() : '',
                active: !!(item && item.classList.contains('active')),
                childCount: childMenu ? childMenu.children.length : 0,
                childDisplay: childMenu ? window.getComputedStyle(childMenu).display : 'none'
            };
        }

        function toggleFallbackMenu(item, childMenu, shouldOpen) {
            if (!item || !childMenu || !item.parentElement) {
                return;
            }

            var $ = window.jQuery;

            [].slice.call(item.parentElement.children || []).forEach(function (sibling) {
                if (!sibling || sibling === item) {
                    return;
                }
                sibling.classList.remove('active');
                var siblingChildMenu = sibling.querySelector(':scope > ul');
                if (siblingChildMenu) {
                    if ($) {
                        $(siblingChildMenu).stop(true, true).slideUp(160);
                    } else {
                        siblingChildMenu.style.display = 'none';
                    }
                }
            });

            item.classList.toggle('active', shouldOpen);
            if ($) {
                $(childMenu).stop(true, true)[shouldOpen ? 'slideDown' : 'slideUp'](160);
            } else {
                childMenu.style.display = shouldOpen ? 'block' : 'none';
            }
        }

        // #region debug-point A:init-sidebar-state
        fetch('http://127.0.0.1:7777/event', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({
                sessionId: 'formtools-sidebar-expand',
                runId: 'pre-fix',
                hypothesisId: 'A',
                location: 'sidebarmenu.blade.php:init',
                msg: '[DEBUG] sidebar init state',
                data: {
                    bodyClassName: document.body ? document.body.className : '',
                    topMenus: [].slice.call(sidebarNav.children || []).map(menuSummary)
                },
                ts: Date.now()
            })
        }).catch(function () {});
        // #endregion

        sidebarNav.addEventListener('click', function (event) {
            var anchor = event.target.closest('a');
            if (!anchor || !sidebarNav.contains(anchor)) {
                return;
            }

            var item = anchor.parentElement;
            if (!item) {
                return;
            }

            var childMenu = item.querySelector(':scope > ul');
            if (!childMenu) {
                return;
            }

            var beforeState = menuSummary(item);

            // #region debug-point B:sidebar-click-before
            fetch('http://127.0.0.1:7777/event', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({
                    sessionId: 'formtools-sidebar-expand',
                    runId: 'pre-fix',
                    hypothesisId: 'B',
                    location: 'sidebarmenu.blade.php:click-before',
                    msg: '[DEBUG] sidebar click before toggle',
                    data: {
                        bodyClassName: document.body ? document.body.className : '',
                        clickedTitle: beforeState.title,
                        clickedActive: beforeState.active,
                        childDisplay: beforeState.childDisplay,
                        childCount: beforeState.childCount,
                        defaultPrevented: event.defaultPrevented
                    },
                    ts: Date.now()
                })
            }).catch(function () {});
            // #endregion

            window.setTimeout(function () {
                var afterState = menuSummary(item);
                var shouldApplyFallback = beforeState.active === afterState.active
                    && beforeState.childDisplay === afterState.childDisplay;

                // #region debug-point C:sidebar-click-after
                fetch('http://127.0.0.1:7777/event', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({
                        sessionId: 'formtools-sidebar-expand',
                        runId: 'pre-fix',
                        hypothesisId: 'C',
                        location: 'sidebarmenu.blade.php:click-after',
                        msg: '[DEBUG] sidebar click after toggle',
                        data: {
                            bodyClassName: document.body ? document.body.className : '',
                            clickedTitle: afterState.title,
                            clickedActive: afterState.active,
                            childDisplay: afterState.childDisplay,
                            childCount: afterState.childCount,
                            fallbackNeeded: shouldApplyFallback
                        },
                        ts: Date.now()
                    })
                }).catch(function () {});
                // #endregion

                if (!shouldApplyFallback) {
                    return;
                }

                toggleFallbackMenu(item, childMenu, !beforeState.active);

                var fallbackState = menuSummary(item);

                // #region debug-point D:sidebar-fallback-applied
                fetch('http://127.0.0.1:7777/event', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({
                        sessionId: 'formtools-sidebar-expand',
                        runId: 'post-fix',
                        hypothesisId: 'A',
                        location: 'sidebarmenu.blade.php:fallback',
                        msg: '[DEBUG] sidebar fallback applied',
                        data: {
                            clickedTitle: fallbackState.title,
                            clickedActive: fallbackState.active,
                            childDisplay: fallbackState.childDisplay,
                            childCount: fallbackState.childCount
                        },
                        ts: Date.now()
                    })
                }).catch(function () {});
                // #endregion
            }, 24);
        }, true);
    });
</script>
