<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <div>
                <h4 class="page-title mx-member-page-title mb-0 font-size-18">{{ $tig['title'] ?? $tig['subtitle'] }}</h4>
                @if(!empty($tig['description']))
                    <p class="mx-member-page-desc mb-0">{{ $tig['description'] }}</p>
                @endif
            </div>

            <div class="page-title-right">
                <a href="{{ $tig['nav_url'] ?: url('member') }}" class="mx-member-page-back">
                    <i class="mdi mdi-arrow-left"></i>
                    <span>{{ $tig['nav'] ?? '返回会员中心' }}</span>
                </a>
            </div>

        </div>
    </div>
</div>
