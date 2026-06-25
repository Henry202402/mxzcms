(function (global) {
    'use strict';

    function toArray(list) {
        return Array.prototype.slice.call(list || []);
    }

    function init(root) {
        var scope = root && root.querySelectorAll ? root : document;
        initFaq(scope);
        initCarousel(scope);
        initMotion(scope);
        initAnchors(scope);
        initSidebar(scope);
    }

    function initFaq(root) {
        toArray(root.querySelectorAll('.mx-page-faq')).forEach(function (faq) {
            if (faq.dataset.mxFaqReady === '1') {
                return;
            }
            faq.dataset.mxFaqReady = '1';
            var items = toArray(faq.querySelectorAll('[data-faq-item]'));
            if (!items.length) {
                return;
            }
            var hasOpen = items.some(function (item) {
                return item.classList.contains('is-open');
            });
            items.forEach(function (item, index) {
                var trigger = item.querySelector('[data-faq-trigger]');
                var panel = item.querySelector('[data-faq-panel]');
                if (!trigger || !panel) {
                    return;
                }
                var shouldOpen = item.classList.contains('is-open') || (!hasOpen && index === 0);
                setFaqState(item, trigger, panel, shouldOpen);
                trigger.addEventListener('click', function () {
                    var nextOpen = !item.classList.contains('is-open');
                    items.forEach(function (currentItem) {
                        var currentTrigger = currentItem.querySelector('[data-faq-trigger]');
                        var currentPanel = currentItem.querySelector('[data-faq-panel]');
                        if (!currentTrigger || !currentPanel) {
                            return;
                        }
                        setFaqState(currentItem, currentTrigger, currentPanel, currentItem === item ? nextOpen : false);
                    });
                });
            });
        });
    }

    function setFaqState(item, trigger, panel, isOpen) {
        item.classList.toggle('is-open', isOpen);
        trigger.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        panel.hidden = !isOpen;
    }

    function initCarousel(root) {
        toArray(root.querySelectorAll('.mx-page-carousel')).forEach(function (carousel) {
            if (carousel.dataset.mxCarouselReady === '1') {
                return;
            }
            var slides = toArray(carousel.querySelectorAll('[data-carousel-slide]'));
            if (!slides.length) {
                return;
            }
            carousel.dataset.mxCarouselReady = '1';
            var dots = toArray(carousel.querySelectorAll('[data-carousel-dot]'));
            var interval = parseInt(carousel.getAttribute('data-carousel-interval') || '4500', 10);
            if (!interval || interval < 1200) {
                interval = 4500;
            }
            var autoplay = carousel.getAttribute('data-carousel-autoplay') === '1' && slides.length > 1;
            var activeIndex = Math.max(0, slides.findIndex(function (slide) {
                return slide.classList.contains('is-active');
            }));
            var timer = null;

            function setActive(index) {
                activeIndex = (index + slides.length) % slides.length;
                slides.forEach(function (slide, slideIndex) {
                    var isActive = slideIndex === activeIndex;
                    slide.classList.toggle('is-active', isActive);
                    slide.setAttribute('aria-hidden', isActive ? 'false' : 'true');
                });
                dots.forEach(function (dot, dotIndex) {
                    var isActive = dotIndex === activeIndex;
                    dot.classList.toggle('is-active', isActive);
                    dot.setAttribute('aria-pressed', isActive ? 'true' : 'false');
                });
            }

            function stop() {
                if (timer) {
                    clearInterval(timer);
                    timer = null;
                }
            }

            function start() {
                if (!autoplay) {
                    return;
                }
                stop();
                timer = setInterval(function () {
                    setActive(activeIndex + 1);
                }, interval);
            }

            dots.forEach(function (dot) {
                dot.addEventListener('click', function () {
                    var index = parseInt(dot.getAttribute('data-carousel-dot') || '0', 10);
                    setActive(index);
                    start();
                });
            });

            carousel.addEventListener('mouseenter', stop);
            carousel.addEventListener('mouseleave', start);
            carousel.addEventListener('focusin', stop);
            carousel.addEventListener('focusout', start);

            setActive(activeIndex);
            start();
        });
    }

    function initMotion(root) {
        var targets = toArray(root.querySelectorAll('[data-motion-effect]'));
        if (!targets.length) {
            return;
        }
        targets.forEach(function (target) {
            target.classList.add('mx-motion-target');
            target.style.setProperty('--mx-motion-duration', target.getAttribute('data-motion-duration') || '0.7s');
            target.style.setProperty('--mx-motion-delay', target.getAttribute('data-motion-delay') || '0s');
        });
        if (!('IntersectionObserver' in global)) {
            targets.forEach(function (target) {
                target.classList.add('is-visible');
            });
            return;
        }
        var observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (!entry.isIntersecting) {
                    return;
                }
                entry.target.classList.add('is-visible');
                observer.unobserve(entry.target);
            });
        }, {
            rootMargin: '0px 0px -8% 0px',
            threshold: 0.18
        });
        targets.forEach(function (target) {
            if (target.classList.contains('is-visible')) {
                return;
            }
            observer.observe(target);
        });
    }

    function initAnchors(root) {
        toArray(root.querySelectorAll('.mx-page-nav a[href^="#"], .mx-page-sidebar a[href^="#"]')).forEach(function (link) {
            if (link.dataset.mxAnchorReady === '1') {
                return;
            }
            link.dataset.mxAnchorReady = '1';
            link.addEventListener('click', function (event) {
                var href = link.getAttribute('href') || '';
                if (!href || href === '#') {
                    return;
                }
                var target = document.getElementById(href.slice(1));
                if (!target) {
                    return;
                }
                event.preventDefault();
                target.scrollIntoView({behavior: 'smooth', block: 'start'});
            });
        });
    }

    function initSidebar(root) {
        toArray(root.querySelectorAll('.mx-page-sidebar')).forEach(function (sidebar) {
            if (sidebar.dataset.mxSidebarReady === '1') {
                return;
            }
            sidebar.dataset.mxSidebarReady = '1';
            if (sidebar.dataset.sidebarOffset) {
                sidebar.style.setProperty('--mx-sidebar-offset', sidebar.dataset.sidebarOffset);
            }
            bindSidebarDismiss();
            toArray(sidebar.querySelectorAll('[data-sidebar-panel-trigger]')).forEach(function (trigger) {
                trigger.addEventListener('click', function (event) {
                    var panelId = trigger.getAttribute('data-sidebar-panel-trigger') || '';
                    var panel = panelId ? sidebar.querySelector('[data-sidebar-panel="' + panelId + '"]') : null;
                    var isOpen = trigger.getAttribute('aria-expanded') === 'true';
                    event.preventDefault();
                    closeSidebarPanels(isOpen ? '' : panelId);
                    if (!panel || isOpen) {
                        return;
                    }
                    panel.style.setProperty('--mx-sidebar-panel-top', Math.max(0, trigger.offsetTop - 4) + 'px');
                });
            });
            toArray(sidebar.querySelectorAll('[data-sidebar-panel-close]')).forEach(function (button) {
                button.addEventListener('click', function () {
                    closeSidebarPanels('');
                });
            });
            var backTop = sidebar.querySelector('[data-sidebar-backtop]');
            if (backTop) {
                backTop.addEventListener('click', function () {
                    if (global.scrollTo) {
                        global.scrollTo({top: 0, behavior: 'smooth'});
                    }
                });
            }
        });
    }

    function closeSidebarPanels(sidebar, keepId) {
        toArray(sidebar.querySelectorAll('[data-sidebar-panel-trigger]')).forEach(function (trigger) {
            var triggerPanelId = trigger.getAttribute('data-sidebar-panel-trigger') || '';
            trigger.setAttribute('aria-expanded', keepId && keepId === triggerPanelId ? 'true' : 'false');
        });
        toArray(sidebar.querySelectorAll('[data-sidebar-panel]')).forEach(function (panel) {
            var panelId = panel.getAttribute('data-sidebar-panel') || '';
            var isOpen = !!keepId && keepId === panelId;
            panel.hidden = !isOpen;
        });
    }

    function bindSidebarDismiss() {
        if (global.__mxSidebarDismissReady) {
            return;
        }
        global.__mxSidebarDismissReady = true;
        document.addEventListener('click', function (event) {
            toArray(document.querySelectorAll('.mx-page-sidebar')).forEach(function (sidebar) {
                if (!sidebar.contains(event.target)) {
                    closeSidebarPanels(sidebar, '');
                }
            });
        });
        document.addEventListener('keydown', function (event) {
            if (event.key !== 'Escape') {
                return;
            }
            toArray(document.querySelectorAll('.mx-page-sidebar')).forEach(function (sidebar) {
                closeSidebarPanels(sidebar, '');
            });
        });
    }

    global.MxPageRuntime = {
        init: init,
        initFaq: initFaq,
        initCarousel: initCarousel,
        initMotion: initMotion,
        initAnchors: initAnchors,
        initSidebar: initSidebar
    };

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function () {
            init(document);
        });
    } else {
        init(document);
    }
})(window);
