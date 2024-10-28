<div id="content">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <!-- Change Log -->

                <div class="changelog-wrapper js-changelog" style="width: 100%;">
                    <!-- Changelog filters -->
                    <div class="changelog-filters">
                        <label class="changelog-checkbox-label" for="changelog-filter-bug-fix"></label>

                        <!-- Changelog Scroll To -->
                        <div class="changelog-scroll-to">
                            <div style="position: relative;top: -8px;">
                                定位 <i class="pe-7s-angle-down"></i>
                            </div>

                            <ul class="changelog-scroll-to-list">
                                @foreach($list as $l)
                                    <li class="changelog-scroll-to-list-item">
                                        <a href="#" class="js-scroll-to" data-target="#v{{$l['id']}}">
                                            {{$l['title']}}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <!-- End of Changelog Scroll To -->
                    </div>
                    <!-- End of Changelog filters -->

                    <!-- Changelog items -->
                    <div class="changelog-items">
                        @foreach($list as $l)
                            <!-- Changelog item -->
                            <div id="v{{$l['id']}}" class="changelog-item js-changelog-item">
                                <!-- Changelog item header -->
                                <header class="changelog-header">
                                    <h3 class="changelog-version" style="word-break: break-all;">{{$l['title']}}</h3>
                                    <p class="changelog-date">{{$l['date']}}</p>
                                </header>
                                <!-- End of Changelog item header -->
                                <!-- Changelog item description -->
                                <div class="changelog-update-descriptions changelog-update-description" style="padding-left: 0px;">
                                    {!! $l['content'] !!}
                                </div>
                                <!-- End of Changelog item description -->
                                <div class="changelog-link"></div>
                            </div>
                            <!-- End of Changelog item -->
                        @endforeach


                    </div>
                    <!-- End of Changelog items -->

                </div>

                <!-- End of Changelog -->

            </div>
        </div>
    </div>
</div>
