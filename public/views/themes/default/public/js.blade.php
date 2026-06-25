<script src="{{HOME_ASSET}}default/assets/js/all.js"></script>
<script src="{{HOME_ASSET}}default/assets/js/custom.js"></script>
<script src="{{commonAsset('lib/layer/layer/layer.js')}}"></script>
{!! cacheGlobalSettingsByKey('foot_codes') !!}
@foreach(hook("GJsCss") as $v)
    {!! $v !!}
@endforeach
<script>
    window.mxAdTrack = function (trackUrl, payload) {
        if (!trackUrl || !payload || !payload.id) {
            return;
        }
        var query = new URLSearchParams(payload).toString();
        var requestUrl = trackUrl + (trackUrl.indexOf('?') === -1 ? '?' : '&') + query;
        if (navigator.sendBeacon) {
            try {
                navigator.sendBeacon(requestUrl);
                return;
            } catch (e) {}
        }
        var img = new Image();
        img.src = requestUrl;
    };

    window.mxTrackImpression = function (element) {
        if (!element || element.getAttribute('data-show-tracked') === '1') {
            return;
        }
        element.setAttribute('data-show-tracked', '1');
        window.mxAdTrack(element.getAttribute('data-show-url'), {
            id: element.getAttribute('data-show-id'),
            type: element.getAttribute('data-show-type') || '',
            slot: element.getAttribute('data-show-slot') || ''
        });
    };

    window.mxObserveImpressions = function () {
        var targets = document.querySelectorAll('[data-show-id][data-show-url]');
        if (!targets.length) {
            return;
        }

        if ('IntersectionObserver' in window) {
            var observer = new IntersectionObserver(function (entries) {
                entries.forEach(function (entry) {
                    if (entry.isIntersecting || entry.intersectionRatio > 0.25) {
                        window.mxTrackImpression(entry.target);
                        observer.unobserve(entry.target);
                    }
                });
            }, {
                threshold: [0.25]
            });

            targets.forEach(function (element) {
                if (element.getAttribute('data-show-bound') === '1') {
                    return;
                }
                element.setAttribute('data-show-bound', '1');
                observer.observe(element);
            });
            return;
        }

        targets.forEach(function (element) {
            window.mxTrackImpression(element);
        });
    };

    document.addEventListener('click', function (event) {
        var target = event.target.closest('[data-track-id][data-track-url]');
        if (!target) {
            return;
        }
        window.mxAdTrack(target.getAttribute('data-track-url'), {
            id: target.getAttribute('data-track-id'),
            type: target.getAttribute('data-track-type') || '',
            slot: target.getAttribute('data-track-slot') || ''
        });
    });

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', window.mxObserveImpressions);
    } else {
        window.mxObserveImpressions();
    }
    window.addEventListener('load', window.mxObserveImpressions);
</script>
