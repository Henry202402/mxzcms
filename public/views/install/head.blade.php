<!-- 必须的 meta 标签 -->
<meta charset="utf-8">
<title>CMS管理系统在线安装</title>
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<!-- Bootstrap 的 CSS 文件 -->
<link rel="stylesheet" href="{{INSTALL_ASSET}}/assets/css/bootstrap.min.css" crossorigin="anonymous">
<link rel="stylesheet" href="{{INSTALL_ASSET}}/assets/font-awesome/css/font-awesome.min.css" crossorigin="anonymous">
{{--<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">--}}
<link rel="stylesheet" href="{{INSTALL_ASSET}}/assets/css/bootstrap-icons.css">
<style>
    body {
        min-height: 100vh;
        background: #f4f6f9;
        color: #1f2937;
    }

    .install-shell {
        padding-bottom: 110px;
    }

    .install-panel {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        box-shadow: 0 12px 36px rgba(15, 23, 42, 0.06);
    }

    .install-panel__header {
        padding: 22px 24px 0;
    }

    .install-panel__body {
        padding: 24px;
    }

    .install-hero {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        padding: 22px 24px;
        margin-top: 16px;
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        border: 1px solid #e5e7eb;
        border-radius: 18px;
        box-shadow: 0 14px 40px rgba(15, 23, 42, 0.05);
    }

    .install-hero__title {
        margin: 0;
        font-size: 26px;
        font-weight: 700;
    }

    .install-hero__desc {
        margin: 8px 0 0;
        color: #6b7280;
        font-size: 14px;
    }

    .install-version-chip,
    .install-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 600;
    }

    .install-version-chip {
        background: #eef2ff;
        color: #3730a3;
    }

    .install-badge {
        background: #f3f4f6;
        color: #374151;
    }

    .install-step-nav {
        display: flex;
        gap: 14px;
        flex-wrap: wrap;
        margin-top: 18px;
    }

    .install-step-nav__item {
        flex: 1 1 180px;
        min-width: 180px;
        padding: 14px 16px;
        border-radius: 14px;
        border: 1px solid #e5e7eb;
        background: #fff;
    }

    .install-step-nav__item.is-active {
        border-color: #2563eb;
        background: #eff6ff;
        box-shadow: inset 0 0 0 1px rgba(37, 99, 235, 0.08);
    }

    .install-step-nav__step {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 24px;
        height: 24px;
        margin-right: 8px;
        border-radius: 50%;
        background: #111827;
        color: #fff;
        font-size: 12px;
        font-weight: 700;
    }

    .install-step-nav__item.is-active .install-step-nav__step {
        background: #2563eb;
    }

    .install-step-nav__label {
        font-weight: 600;
        color: #111827;
    }

    .install-step-nav__desc {
        margin-top: 8px;
        font-size: 12px;
        color: #6b7280;
    }

    .install-kpi-grid,
    .install-summary-grid,
    .install-form-grid {
        display: grid;
        gap: 16px;
    }

    .install-kpi-grid {
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    }

    .install-summary-grid {
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    }

    .install-kpi,
    .install-summary-card,
    .install-side-card,
    .install-info-card {
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        background: #fff;
    }

    .install-kpi {
        padding: 16px 18px;
    }

    .install-kpi__label {
        font-size: 12px;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: .04em;
    }

    .install-kpi__value {
        margin-top: 8px;
        font-size: 26px;
        font-weight: 700;
        color: #111827;
    }

    .install-kpi__hint {
        margin-top: 6px;
        font-size: 12px;
        color: #6b7280;
    }

    .install-section-title {
        margin-bottom: 12px;
        font-size: 18px;
        font-weight: 700;
        color: #111827;
    }

    .install-section-desc {
        margin: -6px 0 16px;
        color: #6b7280;
        font-size: 13px;
    }

    .install-alert {
        border-radius: 14px;
        border-width: 1px;
        padding: 16px 18px;
    }

    .install-table {
        margin-bottom: 0;
        background: #fff;
    }

    .install-table thead th,
    .install-table td,
    .install-table th {
        vertical-align: middle;
    }

    .install-table thead th {
        border-top: 0;
        background: #f8fafc;
        color: #374151;
        font-weight: 700;
    }

    .install-table .section-row td {
        background: #f8fafc;
        color: #111827;
        font-weight: 700;
        border-top: 0;
    }

    .install-form-grid {
        grid-template-columns: minmax(0, 1.8fr) minmax(280px, 1fr);
        align-items: start;
    }

    .install-side-card,
    .install-info-card,
    .install-summary-card {
        padding: 18px;
    }

    .install-side-card h6,
    .install-info-card h6,
    .install-summary-card h6 {
        margin-bottom: 10px;
        font-size: 15px;
        font-weight: 700;
    }

    .install-meta-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .install-meta-list li {
        display: flex;
        justify-content: space-between;
        gap: 12px;
        padding: 9px 0;
        border-bottom: 1px dashed #e5e7eb;
        font-size: 13px;
    }

    .install-meta-list li:last-child {
        border-bottom: 0;
        padding-bottom: 0;
    }

    .install-meta-list__label {
        color: #6b7280;
    }

    .install-meta-list__value {
        font-weight: 600;
        text-align: right;
        word-break: break-all;
    }

    .install-log {
        max-height: 360px;
        overflow: auto;
        padding: 18px;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        background: #fbfdff;
    }

    .install-log li {
        margin-bottom: 10px;
    }

    .install-result-actions {
        display: flex;
        justify-content: center;
        gap: 12px;
        flex-wrap: wrap;
    }

    .install-bottom-actions {
        display: flex;
        justify-content: center;
        gap: 12px;
        flex-wrap: wrap;
        margin-top: 24px;
    }

    .install-muted {
        color: #6b7280;
    }

    code {
        color: #b91c1c;
        background: #fff7ed;
        padding: 2px 6px;
        border-radius: 8px;
    }

    @media (max-width: 991.98px) {
        .install-form-grid {
            grid-template-columns: 1fr;
        }

        .install-hero {
            flex-direction: column;
            align-items: flex-start;
        }
    }
</style>
