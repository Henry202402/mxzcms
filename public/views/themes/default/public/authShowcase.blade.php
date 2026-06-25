@php
    $authEyebrow = $authEyebrow ?? 'Account Center';
    $authTitle = $authTitle ?? '欢迎来到站点账户中心';
    $authDescription = $authDescription ?? '统一管理登录、注册、找回密码与账户安全，获得更完整的站点服务体验。';
    $authHighlights = $authHighlights ?? [
        '现代化交互界面，统一桌面端与移动端体验',
        '注册协议、验证码与登录安全能力完整接入',
        '登录后可直接进入统一升级后的会员中心',
    ];
@endphp

<div class="mx-auth-showcase">
    <span class="mx-auth-showcase__eyebrow">{{ $authEyebrow }}</span>
    <h1 class="mx-auth-showcase__title">{{ $authTitle }}</h1>
    <p class="mx-auth-showcase__description">{{ $authDescription }}</p>

    <div class="mx-auth-showcase__list">
        @foreach($authHighlights as $highlight)
            <div class="mx-auth-showcase__item">
                <i class="fa fa-check-circle"></i>
                <span>{{ $highlight }}</span>
            </div>
        @endforeach
    </div>

    <div class="mx-auth-showcase__meta">
        <span><i class="fa fa-shield"></i> 安全验证</span>
        <span><i class="fa fa-bolt"></i> 统一体验</span>
        <span><i class="fa fa-user-circle"></i> 会员中心</span>
    </div>
</div>
