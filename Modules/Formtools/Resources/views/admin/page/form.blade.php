@php($moduleName = $pageData['moduleName'] ?? 'Formtools')
@include(moduleAdminTemplate($moduleName)."public.header")
<style>
    @import url('{{ asset('views/modules/formtools/assets/page-core.css') }}');

    .ft-page-form {
        display: grid;
        gap: 18px;
    }

    .ft-page-form__note {
        margin-bottom: 0;
        border-radius: 16px;
        border: 1px solid #dbeafe;
        background: linear-gradient(135deg, #eff6ff 0%, #f8fbff 100%);
        color: #1e3a8a;
    }

    .ft-page-form__panel {
        overflow: hidden;
        border-radius: 20px;
        background: #fff;
        border: 1px solid #e5e7eb;
        box-shadow: 0 16px 34px rgba(15, 23, 42, 0.08);
    }

    .ft-page-form__panel--builder {
        overflow: visible;
    }

    .ft-page-form__panel-header {
        display: flex;
        justify-content: space-between;
        gap: 20px;
        padding: 22px 24px 16px;
        border-bottom: 1px solid #eef2f7;
    }

    .ft-page-form__panel-title {
        margin: 0 0 8px;
        color: #0f172a;
        font-size: 18px;
        font-weight: 700;
    }

    .ft-page-form__panel-desc {
        margin: 0;
        color: #64748b;
        line-height: 1.8;
    }

    .ft-page-form__body {
        padding: 24px;
    }

    .ft-page-form__grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 18px 20px;
    }

    .ft-page-form__full {
        grid-column: 1 / -1;
    }

    .ft-page-form__label {
        display: block;
        margin-bottom: 8px;
        color: #334155;
        font-size: 13px;
        font-weight: 700;
    }

    .ft-page-form__help {
        margin-top: 8px;
        color: #94a3b8;
        font-size: 12px;
        line-height: 1.7;
    }

    .ft-page-form__control,
    .ft-page-form textarea,
    .ft-page-form select {
        width: 100%;
        border-radius: 12px;
        border: 1px solid #dbe2ea;
        padding: 10px 12px;
        color: #0f172a;
        transition: border-color .2s ease, box-shadow .2s ease;
    }

    .ft-page-form__control:focus,
    .ft-page-form textarea:focus,
    .ft-page-form select:focus {
        border-color: #60a5fa;
        box-shadow: 0 0 0 3px rgba(96, 165, 250, 0.16);
        outline: none;
    }

    .ft-page-form textarea {
        min-height: 160px;
        resize: vertical;
        font-family: Consolas, Monaco, monospace;
        line-height: 1.7;
    }

    .ft-page-form__radio-group {
        display: flex;
        flex-wrap: wrap;
        gap: 18px;
        padding-top: 10px;
    }

    .ft-page-form__radio {
        display: inline-flex;
        align-items: center;
        color: #334155;
        font-weight: 600;
        gap: 10px;
    }

    .ft-page-form__toolbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        margin-bottom: 14px;
    }

    .ft-page-form__toolbar-right {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .ft-page-builder__theme-panel {
        display: grid;
        gap: 14px;
        padding: 16px;
        border: 1px solid #dbeafe;
        border-radius: 20px;
        background: linear-gradient(135deg, #f8fbff 0%, #eef4ff 100%);
    }

    .ft-page-builder__theme-fold {
        margin-bottom: 14px;
    }

    .ft-page-builder__theme-summary {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-left: auto;
        margin-right: 8px;
        align-items: center;
    }

    .ft-page-builder__theme-summary-chip {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        min-height: 26px;
        padding: 0 10px;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.92);
        border: 1px solid #dbeafe;
        color: #334155;
        font-size: 12px;
        font-weight: 600;
    }

    .ft-page-builder__theme-summary-swatch {
        width: 12px;
        height: 12px;
        border-radius: 999px;
        border: 1px solid rgba(148, 163, 184, 0.35);
        background: #2563eb;
        box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.24);
    }

    .ft-page-builder__theme-head {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 16px;
    }

    .ft-page-builder__theme-title {
        margin: 0;
        font-size: 16px;
        font-weight: 700;
        color: #0f172a;
    }

    .ft-page-builder__theme-desc {
        margin: 4px 0 0;
        color: #475569;
        font-size: 13px;
        line-height: 1.7;
    }

    .ft-page-builder__theme-presets {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .ft-page-builder__theme-chip {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 30px;
        padding: 0 12px;
        border-radius: 999px;
        border: 1px solid #cbd5e1;
        background: #fff;
        color: #334155;
        cursor: pointer;
        transition: all .2s ease;
    }

    .ft-page-builder__theme-chip:hover {
        border-color: #93c5fd;
        color: #1d4ed8;
        box-shadow: 0 10px 20px rgba(37, 99, 235, 0.10);
        transform: translateY(-1px);
    }

    .ft-page-builder__theme-fields {
        display: grid;
        gap: 12px;
        grid-template-columns: repeat(4, minmax(0, 1fr));
    }

    .ft-page-builder__theme-field {
        display: grid;
        gap: 6px;
    }

    .ft-page-builder__theme-input {
        display: flex;
        gap: 8px;
        align-items: center;
    }

    .ft-page-builder__theme-swatch {
        width: 36px;
        height: 36px;
        border-radius: 12px;
        border: 1px solid rgba(148, 163, 184, 0.28);
        background: #ffffff;
        box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.4);
    }

    .ft-page-builder__theme-picker {
        width: 40px;
        min-width: 40px;
        height: 36px;
        padding: 2px;
        border: 1px solid rgba(148, 163, 184, 0.28);
        border-radius: 12px;
        background: #ffffff;
        cursor: pointer;
    }

    .ft-page-builder__theme-picker::-webkit-color-swatch-wrapper {
        padding: 0;
    }

    .ft-page-builder__theme-picker::-webkit-color-swatch {
        border: 0;
        border-radius: 9px;
    }

    .ft-page-builder__theme-input .ft-page-form__control {
        flex: 1 1 auto;
    }

    .ft-page-builder__theme-select {
        min-height: 36px;
        border-radius: 12px;
    }

    .ft-page-builder__theme-field label {
        margin: 0;
        font-size: 12px;
        font-weight: 700;
        color: #334155;
    }

    .ft-page-builder__theme-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
    }

    .ft-page-builder__theme-meta {
        color: #64748b;
        font-size: 12px;
        line-height: 1.7;
    }

    .ft-page-form__preview {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 10px 12px;
        border-radius: 12px;
        background: #f8fafc;
        color: #334155;
        line-height: 1.7;
    }

    .ft-page-form__preview code {
        color: #1d4ed8;
        background: #eff6ff;
        padding: 2px 8px;
        border-radius: 999px;
    }

    .ft-page-form__actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 4px;
    }

    .ft-page-form__actions .btn {
        border-radius: 999px;
        min-width: 120px;
        font-weight: 600;
    }

    .ft-page-form__workspace-meta {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 14px;
        margin-bottom: 4px;
    }

    .ft-page-form__workspace-card {
        padding: 16px 18px;
        border: 1px solid #e5e7eb;
        border-radius: 18px;
        background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
        box-shadow: 0 10px 24px rgba(15, 23, 42, 0.05);
    }

    .ft-page-form__workspace-label {
        margin-bottom: 6px;
        color: #94a3b8;
        font-size: 12px;
        font-weight: 600;
        letter-spacing: .02em;
    }

    .ft-page-form__workspace-value {
        color: #0f172a;
        font-size: 15px;
        font-weight: 700;
        line-height: 1.6;
        word-break: break-all;
    }

    .ft-page-form__workspace-value.is-muted {
        color: #64748b;
        font-weight: 600;
    }

    .ft-page-form__floating-bar {
        position: sticky;
        bottom: 24px;
        z-index: 140;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        margin-top: 14px;
        margin-left: auto;
        margin-right: auto;
        width: min(960px, calc(100% - 120px));
        padding: 12px 16px;
        border: 1px solid rgba(148, 163, 184, 0.24);
        border-radius: 22px;
        background: rgba(15, 23, 42, 0.92);
        box-shadow: 0 20px 44px rgba(15, 23, 42, 0.2);
        backdrop-filter: blur(16px);
    }

    .ft-page-form__floating-main {
        display: flex;
        flex-direction: column;
        gap: 6px;
        min-width: 0;
    }

    .ft-page-form__floating-title {
        color: #f8fafc;
        font-size: 14px;
        font-weight: 700;
        line-height: 1.4;
    }

    .ft-page-form__floating-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        color: #cbd5e1;
        font-size: 12px;
        line-height: 1.7;
    }

    .ft-page-form__floating-chip {
        display: inline-flex;
        align-items: center;
        padding: 4px 10px;
        border-radius: 999px;
        background: rgba(148, 163, 184, 0.16);
        color: #e2e8f0;
    }

    .ft-page-form__floating-chip.is-warning {
        background: rgba(245, 158, 11, 0.18);
        color: #fde68a;
    }

    .ft-page-form__floating-chip.is-success {
        background: rgba(16, 185, 129, 0.18);
        color: #bbf7d0;
    }

    .ft-page-form__floating-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        align-items: center;
        justify-content: flex-end;
    }

    .ft-page-form__floating-actions .btn {
        border-radius: 999px;
        min-width: 118px;
        font-weight: 700;
    }

    .ft-page-form__catalog {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 16px;
    }

    .ft-page-form__catalog-item {
        padding: 16px;
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        background: #f8fafc;
    }

    .ft-page-form__catalog-title {
        margin: 0 0 8px;
        font-size: 15px;
        font-weight: 700;
        color: #0f172a;
    }

    .ft-page-form__catalog-meta {
        display: inline-block;
        margin-bottom: 10px;
        padding: 2px 8px;
        border-radius: 999px;
        background: #eff6ff;
        color: #1d4ed8;
        font-family: Consolas, Monaco, monospace;
        font-size: 12px;
    }

    .ft-page-form__catalog-desc {
        margin: 0 0 12px;
        color: #64748b;
        line-height: 1.8;
    }

    .ft-page-form__catalog-code {
        margin: 0;
        padding: 12px;
        border-radius: 12px;
        background: #0f172a;
        color: #e2e8f0;
        font-family: Consolas, Monaco, monospace;
        font-size: 12px;
        line-height: 1.8;
        white-space: pre-wrap;
        word-break: break-all;
    }

    .ft-page-builder {
        display: grid;
        grid-template-columns: 220px minmax(0, 1fr) 340px;
        gap: 10px;
        margin-bottom: 14px;
        align-items: start;
    }

    .ft-page-builder__panel {
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        background: linear-gradient(180deg, #f3f6fb 0%, #eef3f8 100%);
        min-height: 500px;
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.65), 0 10px 24px rgba(15, 23, 42, 0.05);
        overflow: hidden;
    }

    .ft-page-builder__panel--overlay,
    .ft-page-builder__panel--canvas {
        position: relative;
        overflow: visible;
        z-index: 1;
    }

    .ft-page-builder__panel--catalog {
        z-index: 30;
    }

    .ft-page-builder__inspector-track {
        position: relative;
        min-height: 500px;
        align-self: start;
    }

    .ft-page-builder__panel--inspector {
        position: relative;
        top: 0;
        z-index: 20;
        align-self: start;
        display: grid;
        grid-template-rows: auto minmax(0, 1fr);
        height: calc(100vh - 24px);
        min-height: 0;
        overflow: hidden;
        will-change: transform;
    }

    .ft-page-builder__panel--inspector.is-fixed {
        position: fixed;
        top: 16px;
        left: var(--inspector-fixed-left, auto);
        width: var(--inspector-fixed-width, 340px);
        z-index: 60;
    }

    .ft-page-builder__panel--inspector.is-at-bottom {
        position: absolute;
        top: auto;
        right: 0;
        bottom: 0;
        left: 0;
        width: auto;
        z-index: 20;
    }

    .ft-page-builder__panel-header {
        position: relative;
        padding: 10px 12px;
        border-bottom: 1px solid #e5e7eb;
        background: linear-gradient(180deg, rgba(255, 255, 255, 0.96) 0%, rgba(248, 250, 252, 0.92) 100%);
        z-index: 40;
    }

    .ft-page-builder__panel-head {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 10px;
    }

    .ft-page-builder__panel-kicker {
        display: inline-flex;
        align-items: center;
        margin-bottom: 4px;
        padding: 2px 7px;
        border-radius: 999px;
        background: rgba(148, 163, 184, 0.12);
        color: #475569;
        font-size: 9px;
        font-weight: 800;
        letter-spacing: .08em;
        text-transform: uppercase;
        line-height: 1.2;
    }

    .ft-page-builder__panel-actions {
        position: relative;
        z-index: 100;
        display: flex;
        align-items: center;
        gap: 8px;
        flex-shrink: 0;
    }

    .ft-page-builder__panel-title {
        margin: 0 0 2px;
        font-size: 13px;
        font-weight: 700;
        color: #0f172a;
    }

    .ft-page-builder__panel-desc {
        display: none;
    }

    .ft-page-builder__panel-tip {
        position: relative;
        z-index: 2;
    }

    .ft-page-builder__panel-tip.is-open {
        z-index: 120;
    }

    .ft-page-builder__panel-tip-toggle {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 24px;
        height: 24px;
        border: 1px solid #dbe2ea;
        border-radius: 50%;
        background: #fff;
        color: #475569;
        font-size: 12px;
        font-weight: 800;
        cursor: pointer;
        transition: all .2s ease;
    }

    .ft-page-builder__panel-tip-toggle:hover,
    .ft-page-builder__panel-tip.is-open .ft-page-builder__panel-tip-toggle {
        border-color: #bfdbfe;
        background: #eff6ff;
        color: #1d4ed8;
    }

    .ft-page-builder__panel-tip-body {
        display: none;
        position: absolute;
        top: 30px;
        right: 0;
        z-index: 160;
        width: 280px;
        max-width: min(280px, calc(100vw - 48px));
        padding: 12px;
        border: 1px solid #dbe2ea;
        border-radius: 14px;
        background: rgba(255, 255, 255, 0.98);
        box-shadow: 0 16px 32px rgba(15, 23, 42, 0.10);
        backdrop-filter: blur(12px);
        white-space: normal;
        overflow-wrap: anywhere;
    }

    .ft-page-builder__panel-tip.is-open .ft-page-builder__panel-tip-body {
        display: block;
    }

    .ft-page-builder__panel-tip[data-panel-tip="catalog"] .ft-page-builder__panel-tip-body {
        top: -2px;
        left: calc(100% + 10px);
        right: auto;
    }

    .ft-page-builder__panel-tip-section + .ft-page-builder__panel-tip-section {
        margin-top: 12px;
        padding-top: 12px;
        border-top: 1px solid #eef2f7;
    }

    .ft-page-builder__panel-tip-title {
        display: block;
        margin-bottom: 6px;
        color: #0f172a;
        font-size: 12px;
        font-weight: 700;
    }

    .ft-page-builder__panel-tip-text {
        margin: 0;
        color: #64748b;
        font-size: 12px;
        line-height: 1.75;
        overflow-wrap: anywhere;
    }

    .ft-page-builder__panel-body {
        position: relative;
        padding: 10px;
        max-height: 600px;
        overflow: auto;
    }

    .ft-page-builder__panel-body--catalog {
        padding: 10px;
    }

    .ft-page-builder__panel-body--canvas {
        display: grid;
        gap: 10px;
        padding: 10px;
    }

    .ft-page-builder__panel-body--inspector {
        padding: 10px;
        min-height: 0;
        max-height: none;
        overflow: auto;
    }

    .ft-page-builder__panel--canvas .ft-page-builder__panel-body {
        overflow: visible;
        max-height: none;
    }

    .ft-page-builder__catalog-list {
        display: grid;
        gap: 8px;
    }

    .ft-page-builder__catalog-shell {
        display: grid;
        grid-template-columns: 54px minmax(0, 1fr);
        gap: 8px;
        align-items: start;
        min-height: 0;
    }

    .ft-page-builder__catalog-toolbox {
        position: sticky;
        top: 0;
        display: grid;
        gap: 6px;
        padding: 6px;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        background: #fff;
    }

    .ft-page-builder__catalog-main {
        min-width: 0;
        display: grid;
        gap: 8px;
        padding: 8px;
        border: 1px solid #dbe2ea;
        border-radius: 14px;
        background: linear-gradient(180deg, rgba(255, 255, 255, 0.94) 0%, rgba(248, 250, 252, 0.88) 100%);
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.72);
    }

    .ft-page-builder__catalog-tool {
        display: grid;
        justify-items: center;
        gap: 3px;
        width: 100%;
        padding: 6px 3px;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        background: #fff;
        color: #475569;
        cursor: pointer;
        transition: all .2s ease;
    }

    .ft-page-builder__catalog-tool:hover {
        border-color: #bfdbfe;
        background: #eff6ff;
        color: #1d4ed8;
    }

    .ft-page-builder__catalog-tool.is-active {
        border-color: #2563eb;
        background: linear-gradient(180deg, #eff6ff 0%, #dbeafe 100%);
        color: #1d4ed8;
        box-shadow: 0 10px 24px rgba(37, 99, 235, 0.12);
    }

    .ft-page-builder__catalog-tool-mark {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 24px;
        height: 24px;
        border-radius: 8px;
        background: rgba(148, 163, 184, 0.12);
        font-size: 11px;
        font-weight: 800;
        line-height: 1;
    }

    .ft-page-builder__catalog-tool.is-active .ft-page-builder__catalog-tool-mark {
        background: rgba(37, 99, 235, 0.14);
    }

    .ft-page-builder__catalog-tool-label {
        display: none;
    }

    .ft-page-builder__catalog-toolbar-head {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 10px;
    }

    .ft-page-builder__catalog-current {
        min-width: 0;
    }

    .ft-page-builder__catalog-current-title {
        margin: 0;
        color: #0f172a;
        font-size: 12px;
        font-weight: 700;
    }

    .ft-page-builder__catalog-current-meta {
        display: none;
    }

    .ft-page-builder__catalog-section {
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        background: #fff;
        overflow: hidden;
    }

    .ft-page-builder__catalog-section-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        padding: 12px 14px;
        border: 0;
        width: 100%;
        background: #fff;
        text-align: left;
        cursor: pointer;
    }

    .ft-page-builder__catalog-section-title {
        margin: 0;
        color: #0f172a;
        font-size: 13px;
        font-weight: 700;
    }

    .ft-page-builder__catalog-section-meta {
        margin-top: 4px;
        color: #64748b;
        font-size: 11px;
        line-height: 1.6;
    }

    .ft-page-builder__catalog-section-toggle {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 24px;
        height: 24px;
        border-radius: 999px;
        background: #f8fafc;
        color: #475569;
        font-size: 11px;
        font-weight: 700;
        flex-shrink: 0;
        transition: transform .2s ease, background .2s ease, color .2s ease;
    }

    .ft-page-builder__catalog-section.is-open .ft-page-builder__catalog-section-toggle {
        transform: rotate(180deg);
        background: #eff6ff;
        color: #1d4ed8;
    }

    .ft-page-builder__catalog-section-body {
        display: none;
        padding: 0 10px 10px;
        border-top: 1px solid #f1f5f9;
    }

    .ft-page-builder__catalog-section.is-open .ft-page-builder__catalog-section-body {
        display: block;
    }

    .ft-page-builder__catalog-section-list {
        display: grid;
        gap: 6px;
        padding-top: 8px;
    }

    .ft-page-builder__catalog-section-more {
        margin-top: 10px;
        display: flex;
        justify-content: center;
    }

    .ft-page-builder__catalog-toolbar {
        position: sticky;
        top: -10px;
        z-index: 3;
        display: grid;
        gap: 6px;
        margin: -10px -10px 0;
        padding: 8px 8px 6px;
        border-bottom: 1px solid rgba(219, 226, 234, 0.9);
        background: linear-gradient(180deg, rgba(248, 250, 252, 0.98) 0%, rgba(248, 250, 252, 0.94) 78%, rgba(248, 250, 252, 0) 100%);
        backdrop-filter: blur(10px);
    }

    .ft-page-builder__catalog-meta {
        color: #64748b;
        font-size: 12px;
        line-height: 1.7;
        overflow-wrap: anywhere;
    }

    .ft-page-builder__catalog-quick {
        display: grid;
        gap: 6px;
        margin-bottom: 10px;
    }

    .ft-page-builder__catalog-quick:empty {
        display: none;
        margin-bottom: 0;
    }

    .ft-page-builder__catalog-quick-title {
        margin: 0;
        color: #0f172a;
        font-size: 12px;
        font-weight: 700;
    }

    .ft-page-builder__catalog-quick-list {
        display: flex;
        gap: 6px;
        flex-wrap: nowrap;
        overflow-x: auto;
        overflow-y: hidden;
        padding-bottom: 4px;
        scrollbar-width: thin;
    }

    .ft-page-builder__catalog-quick-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 30px;
        padding: 5px 10px;
        border: 1px solid #dbe2ea;
        border-radius: 999px;
        background: #fff;
        color: #334155;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: all .2s ease;
        white-space: nowrap;
        flex: 0 0 auto;
    }

    .ft-page-builder__catalog-quick-btn:hover {
        border-color: #bfdbfe;
        background: #eff6ff;
        color: #1d4ed8;
    }

    .ft-page-builder__catalog-target {
        padding: 12px 14px;
        border: 1px dashed #cbd5e1;
        border-radius: 14px;
        background: #fff;
        color: #64748b;
        font-size: 12px;
        line-height: 1.8;
        overflow-wrap: anywhere;
    }

    .ft-page-builder__catalog-card {
        padding: 8px;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        background: #fff;
        cursor: pointer;
        transition: border-color .2s ease, box-shadow .2s ease, transform .2s ease;
    }

    .ft-page-builder__catalog-card:hover {
        border-color: #bfdbfe;
        box-shadow: 0 12px 28px rgba(37, 99, 235, 0.08);
        transform: translateY(-1px);
    }

    .ft-page-builder__catalog-card-head {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 8px;
        margin-bottom: 4px;
    }

    .ft-page-builder__catalog-card h5 {
        margin: 0;
        font-size: 12px;
        font-weight: 700;
        color: #0f172a;
        line-height: 1.4;
    }

    .ft-page-builder__catalog-card p {
        display: none;
    }

    .ft-page-builder__catalog-actions {
        display: flex;
        gap: 6px;
        align-items: center;
        justify-content: flex-start;
    }

    .ft-page-builder__catalog-type {
        display: inline-flex;
        align-items: center;
        padding: 2px 8px;
        border-radius: 999px;
        background: #eff6ff;
        color: #1d4ed8;
        font-family: Consolas, Monaco, monospace;
        font-size: 10px;
    }

    .ft-page-builder__catalog-card .btn {
        flex-shrink: 0;
    }

    .ft-page-builder__canvas-toolbar {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 0;
        padding: 8px 10px;
        border: 1px solid #dbe2ea;
        border-radius: 14px;
        background: #fff;
        box-shadow: none;
    }

    .ft-page-builder__canvas-toolbar-chip,
    .ft-page-builder__empty-kicker {
        display: inline-flex;
        align-items: center;
        width: fit-content;
        padding: 2px 7px;
        border-radius: 999px;
        background: rgba(148, 163, 184, 0.12);
        color: #475569;
        font-size: 9px;
        font-weight: 800;
        letter-spacing: .08em;
        text-transform: uppercase;
        line-height: 1.2;
    }

    .ft-page-builder__canvas-actions {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .ft-page-builder__canvas-actions .btn {
        border-radius: 12px;
    }

    .ft-page-builder__canvas-status {
        padding: 12px 14px;
        border: 1px solid #dbeafe;
        border-radius: 14px;
        background: linear-gradient(135deg, #eff6ff 0%, #f8fbff 100%);
        color: #1e3a8a;
        line-height: 1.7;
        font-size: 12px;
    }

    .ft-page-builder__canvas-status code {
        color: #1d4ed8;
        background: rgba(255, 255, 255, 0.75);
        padding: 2px 8px;
        border-radius: 999px;
        white-space: normal;
        overflow-wrap: anywhere;
    }

    .ft-page-builder__notice {
        display: none;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        position: sticky;
        top: 12px;
        z-index: 9;
        margin-bottom: 14px;
        padding: 12px 14px;
        border: 1px solid #dbeafe;
        border-radius: 14px;
        background: #eff6ff;
        color: #1d4ed8;
        box-shadow: 0 10px 24px rgba(15, 23, 42, 0.08);
    }

    .ft-page-builder__notice.is-success {
        border-color: #bbf7d0;
        background: #f0fdf4;
        color: #15803d;
    }

    .ft-page-builder__notice.is-warning {
        border-color: #fde68a;
        background: #fffbeb;
        color: #b45309;
    }

    .ft-page-builder__notice.is-error {
        border-color: #fecaca;
        background: #fef2f2;
        color: #b91c1c;
    }

    .ft-page-builder__notice-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .ft-page-builder__selection-bar,
    .ft-page-builder__inspector-summary {
        display: none;
        gap: 12px;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 0;
        padding: 12px 14px;
        border: 1px solid #dbe2ea;
        border-radius: 16px;
        background: #fff;
        box-shadow: none;
    }

    .ft-page-builder__selection-main strong,
    .ft-page-builder__inspector-summary strong {
        display: block;
        margin-bottom: 4px;
        color: #0f172a;
        font-size: 14px;
    }

    .ft-page-builder__selection-main span,
    .ft-page-builder__inspector-summary span {
        display: block;
        color: #64748b;
        font-size: 12px;
        line-height: 1.7;
    }

    .ft-page-builder__inspector-outline {
        display: none;
        margin-bottom: 14px;
        border-radius: 16px;
        border: 1px solid #dbe2ea;
        background: #fff;
        overflow: hidden;
    }

    .ft-page-builder__inspector-outline.is-visible {
        display: block;
    }

    .ft-page-builder__inspector-outline-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        width: 100%;
        padding: 12px 14px;
        border: 0;
        background: transparent;
        text-align: left;
        cursor: pointer;
    }

    .ft-page-builder__inspector-outline-title {
        margin: 0;
        color: #0f172a;
        font-size: 13px;
        font-weight: 700;
    }

    .ft-page-builder__inspector-outline-summary {
        margin-top: 3px;
        color: #475569;
        font-size: 12px;
        line-height: 1.6;
    }

    .ft-page-builder__inspector-outline-toggle {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 24px;
        height: 24px;
        border-radius: 999px;
        background: #f8fafc;
        color: #475569;
        font-size: 12px;
        font-weight: 700;
        flex-shrink: 0;
        transition: transform .2s ease, background .2s ease;
    }

    .ft-page-builder__inspector-outline.is-expanded .ft-page-builder__inspector-outline-toggle {
        transform: rotate(180deg);
        background: #eef2f7;
    }

    .ft-page-builder__inspector-outline-body {
        display: none;
        padding: 0 14px 14px;
    }

    .ft-page-builder__inspector-outline.is-expanded .ft-page-builder__inspector-outline-body {
        display: block;
    }

    .ft-page-builder__inspector-outline-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 10px;
    }

    .ft-page-builder__inspector-outline-item {
        padding: 10px 12px;
        border-radius: 12px;
        background: rgba(255, 255, 255, 0.78);
        color: #475569;
        font-size: 12px;
        line-height: 1.7;
    }

    .ft-page-builder__inspector-outline-item strong {
        display: block;
        color: #0f172a;
        font-size: 13px;
        margin-bottom: 4px;
    }

    .ft-page-builder__selection-actions,
    .ft-page-builder__inspector-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        justify-content: flex-end;
    }

    .ft-page-builder__breadcrumb {
        display: none;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 0;
        padding: 10px 12px;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        background: #fff;
    }

    .ft-page-builder__breadcrumb.is-visible {
        display: flex;
    }

    .ft-page-builder__breadcrumb-item {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        appearance: none;
        padding: 6px 10px;
        border-radius: 999px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        color: #475569;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
    }

    .ft-page-builder__breadcrumb-item.is-current {
        border-color: #94a3b8;
        background: #fff;
        color: #0f172a;
        box-shadow: 0 0 0 2px rgba(148, 163, 184, 0.18);
    }

    .ft-page-builder__inspector-tabs {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        margin-bottom: 0;
        margin: -12px -12px 2px;
        padding: 8px 12px 10px;
        border-bottom: 1px solid #e5e7eb;
        background: rgba(248, 250, 252, 0.92);
    }

    .ft-page-builder__inspector-tab {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0;
        min-width: 36px;
        min-height: 32px;
        padding: 4px 10px;
        border: 1px solid #dbe2ea;
        border-radius: 999px;
        background: linear-gradient(180deg, rgba(255, 255, 255, 0.94) 0%, rgba(248, 250, 252, 0.88) 100%);
        color: #475569;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: all .2s ease;
    }

    .ft-page-builder__inspector-tab:hover {
        border-color: #bfdbfe;
        color: #1d4ed8;
        transform: translateY(-1px);
    }

    .ft-page-builder__inspector-tab.is-active {
        border-color: #93c5fd;
        background: linear-gradient(180deg, #eff6ff 0%, #dbeafe 100%);
        color: #1d4ed8;
        box-shadow: 0 10px 24px rgba(37, 99, 235, 0.12);
    }

    .ft-page-builder__inspector-tab-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 20px;
        height: 20px;
        border-radius: 999px;
        background: transparent;
        color: #475569;
        font-size: 12px;
        font-weight: 800;
        line-height: 1;
    }

    .ft-page-builder__inspector-tab.is-active .ft-page-builder__inspector-tab-icon {
        color: #1d4ed8;
    }

    .ft-page-builder__inspector-tab-label {
        display: none;
    }

    .ft-page-builder__inspector-panel {
        display: none;
    }

    .ft-page-builder__inspector-panel.is-active {
        display: block;
    }

    .ft-page-builder__inspector-panel + .ft-page-builder__inspector-panel {
        margin-top: 0;
    }

    .ft-page-builder__inspector-sticky-actions {
        display: none;
        position: sticky;
        top: -1px;
        z-index: 3;
        margin: -12px -12px 12px;
        padding: 10px 12px;
        border-bottom: 1px solid #e5e7eb;
        background: rgba(248, 250, 252, 0.94);
        backdrop-filter: blur(10px);
    }

    .ft-page-builder__inspector-sticky-actions.is-visible {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        justify-content: space-between;
        align-items: center;
    }

    .ft-page-builder__inspector-sticky-meta {
        color: #64748b;
        font-size: 12px;
        line-height: 1.7;
    }

    .ft-page-builder__inspector-sticky-buttons {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .ft-page-builder__fold {
        margin-bottom: 0;
        border: 1px solid #dbe2ea;
        border-radius: 14px;
        background: rgba(255, 255, 255, 0.84);
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.72);
        overflow: hidden;
    }

    .ft-page-builder__fold-summary {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        padding: 8px 10px;
        cursor: pointer;
        list-style: none;
        user-select: none;
    }

    .ft-page-builder__fold-summary::-webkit-details-marker {
        display: none;
    }

    .ft-page-builder__fold-title {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        color: #0f172a;
        font-size: 12px;
        font-weight: 700;
    }

    .ft-page-builder__fold-title::before {
        content: '+';
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 16px;
        height: 16px;
        border-radius: 999px;
        border: 1px solid #dbe2ea;
        background: #fff;
        color: #64748b;
        font-size: 11px;
        line-height: 1;
    }

    .ft-page-builder__fold[open] .ft-page-builder__fold-title::before {
        content: '-';
    }

    .ft-page-builder__fold-meta {
        color: #64748b;
        font-size: 10px;
        line-height: 1.4;
    }

    .ft-page-builder__fold-body {
        padding: 0 10px 10px;
        border-top: 1px solid #eef2f7;
    }

    .ft-page-builder__preset-list {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        padding-top: 8px;
    }

    .ft-page-builder__preset-list .btn {
        min-width: 72px;
        border-radius: 10px;
    }

    .ft-page-builder__canvas-stage {
        display: grid;
        gap: 10px;
        padding: 10px;
        border: 1px solid #dbe2ea;
        border-radius: 14px;
        background: #fff;
        box-shadow: none;
    }

    .ft-page-builder__inspector-shell {
        display: block;
        min-height: 0;
    }

    .ft-page-builder__inspector-rail {
        display: none;
    }

    .ft-page-builder__inspector-main {
        min-width: 0;
        display: grid;
        gap: 10px;
        padding: 12px;
        border: 1px solid #dbe2ea;
        border-radius: 14px;
        background: linear-gradient(180deg, rgba(255, 255, 255, 0.94) 0%, rgba(248, 250, 252, 0.88) 100%);
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.72);
    }

    .ft-page-builder__image-tools {
        display: grid;
        gap: 12px;
        margin-bottom: 14px;
    }

    .ft-page-builder__image-preview {
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 180px;
        padding: 14px;
        border: 1px dashed #cbd5e1;
        border-radius: 14px;
        background: #f8fafc;
        overflow: hidden;
    }

    .ft-page-builder__image-preview img {
        max-width: 100%;
        max-height: 240px;
        border-radius: 12px;
        object-fit: contain;
        box-shadow: 0 10px 24px rgba(15, 23, 42, 0.08);
    }

    .ft-page-builder__image-empty {
        color: #94a3b8;
        font-size: 13px;
        text-align: center;
        line-height: 1.7;
    }

    .ft-page-builder__image-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .ft-page-builder__quick-preset-list {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 10px;
    }

    .ft-page-builder__quick-preset-list .btn {
        border-radius: 999px;
    }

    .ft-page-builder__subpanel-actions {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        margin-top: 8px;
    }

    .ft-page-builder__reuse-list {
        display: grid;
        gap: 10px;
        margin-top: 12px;
    }

    .ft-page-builder__reuse-item {
        padding: 12px;
        border: 1px solid #dbe2ea;
        border-radius: 14px;
        background: #f8fafc;
    }

    .ft-page-builder__reuse-head {
        display: flex;
        justify-content: space-between;
        gap: 12px;
        align-items: center;
        margin-bottom: 8px;
    }

    .ft-page-builder__reuse-name {
        margin: 0;
        color: #0f172a;
        font-size: 13px;
        font-weight: 700;
    }

    .ft-page-builder__reuse-meta {
        color: #64748b;
        font-size: 12px;
        line-height: 1.7;
    }

    .ft-page-builder__reuse-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 10px;
    }

    .ft-page-builder__subpanel {
        position: relative;
    }

    .ft-page-builder__subpanel-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 12px;
    }

    .ft-page-builder__subpanel-headcopy {
        min-width: 0;
    }

    .ft-page-builder__subpanel-toggle {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 28px;
        height: 28px;
        padding: 0;
        border: 1px solid #dbe2ea;
        border-radius: 50%;
        background: #fff;
        color: #475569;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
    }

    .ft-page-builder__subpanel-toggle:hover {
        border-color: #bfdbfe;
        color: #1d4ed8;
    }

    .ft-page-builder__subpanel.is-collapsed .ft-page-builder__subpanel-body {
        display: none;
    }

    .ft-page-builder__subpanel.is-collapsed .ft-page-builder__subpanel-header {
        margin-bottom: 0;
    }

    .ft-page-builder__style-presets {
        display: grid;
        gap: 12px;
        margin-top: 12px;
    }

    .ft-page-builder__style-group {
        padding: 12px;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        background: #fff;
    }

    .ft-page-builder__style-group-title {
        margin: 0 0 8px;
        color: #0f172a;
        font-size: 12px;
        font-weight: 700;
    }

    .ft-page-builder__style-group-desc {
        margin: 0 0 10px;
        color: #64748b;
        font-size: 12px;
        line-height: 1.7;
    }

    .ft-page-builder__style-color-list,
    .ft-page-builder__style-chip-list {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .ft-page-builder__style-color {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 6px 10px;
        border: 1px solid #dbe2ea;
        border-radius: 999px;
        background: #fff;
        color: #334155;
        font-size: 12px;
        line-height: 1;
        cursor: pointer;
    }

    .ft-page-builder__style-color-dot {
        width: 16px;
        height: 16px;
        border-radius: 999px;
        border: 1px solid rgba(15, 23, 42, 0.12);
        flex: 0 0 auto;
    }

    .ft-page-builder__style-chip {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 32px;
        padding: 6px 12px;
        border: 1px solid #dbe2ea;
        border-radius: 999px;
        background: #fff;
        color: #334155;
        font-size: 12px;
        line-height: 1;
        cursor: pointer;
    }

    .ft-page-builder__style-color:hover,
    .ft-page-builder__style-chip:hover {
        border-color: #93c5fd;
        color: #1d4ed8;
    }

    .ft-page-builder__color-input {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .ft-page-builder__color-input input[type="color"] {
        width: 40px;
        min-width: 40px;
        height: 36px;
        padding: 4px;
        border: 1px solid #dbe2ea;
        border-radius: 12px;
        background: #fff;
        cursor: pointer;
    }

    .ft-page-builder-preview [data-node-id].is-locating {
        outline: 2px solid #2563eb;
        outline-offset: 4px;
        box-shadow: 0 0 0 8px rgba(37, 99, 235, 0.12);
        transition: outline-color .2s ease, box-shadow .2s ease;
    }

    .ft-page-builder__node.is-locating {
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.18), 0 18px 40px rgba(37, 99, 235, 0.16);
        transition: box-shadow .2s ease;
    }

    .ft-page-builder__spacing-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 8px 10px;
    }

    .ft-page-builder__spacing-title {
        margin: 0 0 8px;
        color: #0f172a;
        font-size: 12px;
        font-weight: 700;
    }

    .ft-page-builder__canvas-empty {
        padding: 34px 20px;
        border: 1px dashed #cbd5e1;
        border-radius: 16px;
        background: #fff;
        text-align: center;
        color: #64748b;
        line-height: 1.8;
    }

    .ft-page-builder__tree {
        display: grid;
        gap: 12px;
    }

    .ft-page-builder__node {
        position: relative;
        border: 1px solid #dbe2ea;
        border-radius: 16px;
        background: #fff;
        box-shadow: 0 4px 10px rgba(15, 23, 42, 0.03);
        transition: border-color .2s ease, box-shadow .2s ease, transform .2s ease;
        overflow: visible;
    }

    .ft-page-builder__node::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        bottom: 0;
        width: 2px;
        background: #e5e7eb;
    }

    .ft-page-builder__node--section::before {
        background: #e5e7eb;
    }

    .ft-page-builder__node--row::before {
        background: #e5e7eb;
    }

    .ft-page-builder__node--column::before {
        background: #e5e7eb;
    }

    .ft-page-builder__node--depth-2 {
        background: #fff;
    }

    .ft-page-builder__node--depth-3,
    .ft-page-builder__node--depth-4 {
        background: #fff;
    }

    .ft-page-builder__node:hover {
        border-color: #94a3b8;
        box-shadow: 0 0 0 2px rgba(148, 163, 184, 0.14);
        transform: translateY(-1px);
    }

    .ft-page-builder__node.is-selected {
        border-color: #60a5fa;
        box-shadow: 0 0 0 3px rgba(96, 165, 250, 0.20);
    }

    .ft-page-builder__node.is-ancestor {
        border-color: #cbd5e1;
        box-shadow: 0 0 0 2px rgba(203, 213, 225, 0.7);
    }

    .ft-page-builder__node.is-parent-of-selected {
        border-color: #94a3b8;
        box-shadow: 0 0 0 2px rgba(148, 163, 184, 0.22);
    }

    .ft-page-builder__node.is-dragging {
        opacity: .6;
    }

    .ft-page-builder__node.is-drop-target {
        border-color: #2563eb;
    }

    .ft-page-builder__node-header {
        display: flex;
        justify-content: space-between;
        gap: 12px;
        padding: 14px 16px;
    }

    .ft-page-builder__node-title {
        margin: 0 0 6px;
        font-size: 14px;
        font-weight: 700;
        color: #0f172a;
    }

    .ft-page-builder__node-title-row {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .ft-page-builder__node-type-mark {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 22px;
        height: 22px;
        border-radius: 8px;
        border: 1px solid #dbe2ea;
        background: #fff;
        color: #475569;
        font-size: 11px;
        font-weight: 800;
        line-height: 1;
        flex-shrink: 0;
    }

    .ft-page-builder__node-type-mark--section {
        background: #fff;
        color: #475569;
    }

    .ft-page-builder__node-type-mark--row {
        background: #fff;
        color: #475569;
    }

    .ft-page-builder__node-type-mark--column {
        background: #fff;
        color: #475569;
    }

    .ft-page-builder__node-meta {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }

    .ft-page-builder__node-badge {
        display: inline-flex;
        align-items: center;
        padding: 2px 8px;
        border-radius: 999px;
        border: 1px solid #e2e8f0;
        background: #fff;
        color: #475569;
        font-family: Consolas, Monaco, monospace;
        font-size: 12px;
    }

    .ft-page-builder__node-preview {
        margin: 8px 0 0;
        color: #64748b;
        line-height: 1.7;
        font-size: 12px;
    }

    .ft-page-builder__node-context {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        margin: 0 16px 10px;
        padding: 6px 8px;
        border-radius: 12px;
        background: #fff;
        border: 1px solid #eef2f7;
    }

    .ft-page-builder__node-context-item {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 2px 8px;
        border-radius: 999px;
        background: #fff;
        color: #475569;
        font-size: 11px;
        line-height: 1.5;
    }

    .ft-page-builder__node-context-item strong {
        color: #0f172a;
        font-size: 11px;
    }

    .ft-page-builder__node-helper {
        margin: 10px 16px 0;
        padding: 8px 10px;
        border-radius: 12px;
        border: 1px dashed #e2e8f0;
        background: #fff;
        color: #64748b;
        font-size: 11px;
        line-height: 1.6;
    }

    .ft-page-builder__node.is-selected .ft-page-builder__node-helper {
        border-color: #93c5fd;
        color: #1d4ed8;
    }

    .ft-page-builder__node:not(.is-selected):not(:hover) .ft-page-builder__node-helper {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .ft-page-builder__insert-zone {
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 18px;
        margin: 0 16px;
        border-radius: 999px;
        color: #94a3b8;
        font-size: 10px;
        line-height: 1;
        transition: all .2s ease;
        position: relative;
    }

    .ft-page-builder__insert-zone::before {
        content: '';
        flex: 1 1 auto;
        height: 1px;
        background: #dbe2ea;
        margin-right: 10px;
    }

    .ft-page-builder__insert-zone::after {
        content: '';
        flex: 1 1 auto;
        height: 1px;
        background: #dbe2ea;
        margin-left: 10px;
    }

    .ft-page-builder__insert-zone span {
        white-space: nowrap;
        max-width: 0;
        overflow: hidden;
        opacity: 0;
        transform: translateX(-2px);
        transition: max-width .2s ease, opacity .2s ease, transform .2s ease;
    }

    .ft-page-builder__insert-zone.is-active {
        color: #1d4ed8;
    }

    .ft-page-builder__insert-zone.is-active::before,
    .ft-page-builder__insert-zone.is-active::after {
        background: #94a3b8;
        height: 2px;
    }

    .ft-page-builder__insert-trigger {
        position: relative;
        z-index: 1;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 0 4px;
        background: transparent;
        border-radius: 999px;
    }

    .ft-page-builder__insert-button {
        width: 22px;
        height: 22px;
        border: 1px solid #dbe2ea;
        border-radius: 999px;
        background: #fff;
        color: #64748b;
        font-size: 14px;
        line-height: 1;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .ft-page-builder__insert-zone:hover .ft-page-builder__insert-button,
    .ft-page-builder__insert-zone.is-menu-open .ft-page-builder__insert-button {
        border-color: #94a3b8;
        color: #0f172a;
        background: #fff;
    }

    .ft-page-builder__insert-zone:hover span,
    .ft-page-builder__insert-zone.is-menu-open span,
    .ft-page-builder__insert-zone.is-active span {
        max-width: 72px;
        opacity: 1;
        transform: translateX(0);
    }

    .ft-page-builder__insert-menu {
        display: none;
        position: absolute;
        top: calc(100% + 8px);
        left: 50%;
        transform: translateX(-50%);
        z-index: 5;
        min-width: 280px;
        padding: 12px;
        border: 1px solid #dbe2ea;
        border-radius: 16px;
        background: #fff;
        box-shadow: 0 16px 36px rgba(15, 23, 42, 0.12);
    }

    .ft-page-builder__insert-zone.is-menu-open .ft-page-builder__insert-menu {
        display: block;
    }

    .ft-page-builder__insert-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 8px;
    }

    .ft-page-builder__insert-item {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        gap: 4px;
        padding: 10px 12px;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        background: #fff;
        color: #334155;
        text-align: left;
        cursor: pointer;
    }

    .ft-page-builder__insert-item strong {
        font-size: 12px;
        color: #0f172a;
    }

    .ft-page-builder__insert-item span {
        font-size: 11px;
        color: #64748b;
        line-height: 1.6;
        white-space: normal;
    }

    .ft-page-builder__insert-item:hover {
        border-color: #bfdbfe;
        background: #f8fbff;
    }

    .ft-page-builder__node-actions {
        display: flex;
        gap: 6px;
        flex-wrap: wrap;
        justify-content: flex-end;
        align-items: flex-start;
        opacity: .22;
        transition: opacity .2s ease;
    }

    .ft-page-builder__node:hover .ft-page-builder__node-actions,
    .ft-page-builder__node.is-selected .ft-page-builder__node-actions {
        opacity: 1;
    }

    .ft-page-builder__node-main-action {
        min-width: 84px;
    }

    .ft-page-builder__node.is-selected .ft-page-builder__node-main-action {
        border-color: #60a5fa;
        background: #eff6ff;
        color: #1d4ed8;
    }

    .ft-page-builder__node:not(.is-selected):not(:hover) .ft-page-builder__node-main-action,
    .ft-page-builder__node:not(.is-selected):not(:hover) [data-builder-action="menu"] {
        opacity: .86;
    }

    .ft-page-builder__node-menu {
        position: relative;
    }

    .ft-page-builder__node-menu-panel {
        display: none;
        position: absolute;
        top: calc(100% + 8px);
        right: 0;
        z-index: 40;
        min-width: 156px;
        padding: 8px;
        border: 1px solid #dbe2ea;
        border-radius: 14px;
        background: #fff;
        box-shadow: 0 18px 36px rgba(15, 23, 42, 0.12);
    }

    .ft-page-builder__node-menu.is-open .ft-page-builder__node-menu-panel {
        display: grid;
        gap: 6px;
    }

    .ft-page-builder__node-menu-item {
        display: flex;
        align-items: center;
        justify-content: flex-start;
        width: 100%;
        min-height: 30px;
        padding: 6px 10px;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        background: #fff;
        color: #334155;
        font-size: 12px;
        font-weight: 600;
        text-align: left;
        cursor: pointer;
        transition: all .2s ease;
    }

    .ft-page-builder__node-menu-item:hover {
        border-color: #93c5fd;
        background: #eff6ff;
        color: #1d4ed8;
    }

    .ft-page-builder__node-menu-item.is-danger:hover {
        border-color: #fecaca;
        background: #fef2f2;
        color: #dc2626;
    }

    .ft-page-builder__dropzone {
        margin: 0 16px 12px 28px;
        padding: 12px 14px;
        border: 1px dashed #cbd5e1;
        border-radius: 12px;
        background: #f8fafc;
        color: #64748b;
        font-size: 12px;
        line-height: 1.7;
        text-align: center;
    }

    .ft-page-builder__dropzone-tip {
        margin-bottom: 10px;
    }

    .ft-page-builder__dropzone-actions {
        display: grid;
        gap: 10px;
    }

    .ft-page-builder__dropzone-group {
        display: grid;
        gap: 8px;
    }

    .ft-page-builder__dropzone-group-title {
        color: #475569;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: .02em;
        text-transform: uppercase;
        text-align: left;
    }

    .ft-page-builder__dropzone-group-list {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 8px;
    }

    .ft-page-builder__dropzone-action {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 30px;
        padding: 6px 12px;
        border: 1px solid #dbe2ea;
        border-radius: 999px;
        background: #ffffff;
        color: #334155;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: all .2s ease;
    }

    .ft-page-builder__dropzone-action:hover {
        border-color: #93c5fd;
        background: #eff6ff;
        color: #1d4ed8;
    }

    .ft-page-builder__dropzone.is-drop-into {
        border-color: #2563eb;
        background: #eff6ff;
        color: #1d4ed8;
    }

    .ft-page-builder__node-scope {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 8px;
        margin: 0 16px 10px 28px;
        padding: 8px 10px;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        background: #f8fbff;
        color: #475569;
        font-size: 11px;
        line-height: 1.5;
    }

    .ft-page-builder__node-scope strong {
        color: #0f172a;
        font-size: 12px;
    }

    .ft-page-builder__node-scope span {
        display: inline-flex;
        align-items: center;
        padding: 2px 8px;
        border-radius: 999px;
        background: #eff6ff;
        color: #1d4ed8;
        font-weight: 700;
    }

    .ft-page-builder__node-scope em {
        font-style: normal;
        color: #64748b;
    }

    .ft-page-builder__node-children {
        position: relative;
        display: grid;
        gap: 12px;
        padding: 0 16px 16px 28px;
    }

    .ft-page-builder__node-children::before {
        content: '';
        position: absolute;
        top: 0;
        bottom: 16px;
        left: 18px;
        width: 2px;
        border-radius: 999px;
        background: linear-gradient(180deg, rgba(96, 165, 250, 0.08) 0%, rgba(96, 165, 250, 0.4) 100%);
    }

    .ft-page-builder__inspector-empty {
        display: grid;
        gap: 6px;
        padding: 12px;
        border: 1px solid #dbe2ea;
        border-radius: 14px;
        background: linear-gradient(180deg, rgba(255, 255, 255, 0.94) 0%, rgba(248, 250, 252, 0.88) 100%);
        color: #64748b;
        line-height: 1.6;
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.72);
    }

    .ft-page-builder__inspector-empty strong {
        color: #0f172a;
        font-size: 13px;
    }

    .ft-page-builder__inspector-empty span {
        display: block;
        font-size: 11px;
    }

    .ft-page-builder__field {
        margin-bottom: 10px;
    }

    .ft-page-builder__field label {
        display: block;
        margin-bottom: 4px;
        color: #334155;
        font-size: 11px;
        font-weight: 700;
        line-height: 1.4;
    }

    .ft-page-builder__field textarea,
    .ft-page-builder__field input,
    .ft-page-builder__field select {
        width: 100%;
        border-radius: 10px;
        border: 1px solid #dbe2ea;
        min-height: 34px;
        padding: 6px 10px;
        font-size: 11px;
        line-height: 1.5;
        background: #fff;
    }

    .ft-page-builder__field textarea {
        min-height: 90px;
        resize: vertical;
        font-family: Consolas, Monaco, monospace;
    }

    .ft-page-builder__field input:focus,
    .ft-page-builder__field textarea:focus,
    .ft-page-builder__field select:focus {
        border-color: #60a5fa;
        box-shadow: 0 0 0 3px rgba(96, 165, 250, 0.16);
        outline: none;
    }

    .ft-page-builder__field-help {
        margin-top: 4px;
        color: #94a3b8;
        font-size: 11px;
        line-height: 1.5;
    }

    .ft-page-builder__field-help.is-static {
        display: none;
    }

    .ft-page-builder__field-help.is-status {
        padding: 8px 10px;
        border-radius: 12px;
        background: #f8fafc;
        color: #64748b;
    }

    .ft-page-builder__hint {
        margin-top: 6px;
        position: relative;
        z-index: 1;
    }

    .ft-page-builder__hint--block {
        margin-bottom: 14px;
    }

    .ft-page-builder__hint.is-open {
        z-index: 80;
    }

    .ft-page-builder__hint-toggle {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 22px;
        height: 22px;
        border: 1px solid #dbe2ea;
        border-radius: 50%;
        background: #fff;
        color: #64748b;
        font-size: 12px;
        font-weight: 700;
        cursor: pointer;
        transition: all .2s ease;
        position: relative;
        z-index: 2;
    }

    .ft-page-builder__hint-toggle:hover {
        border-color: #bfdbfe;
        background: #eff6ff;
        color: #1d4ed8;
    }

    .ft-page-builder__hint-body {
        display: none;
        position: relative;
        z-index: 3;
        margin-top: 8px;
        padding: 10px 12px;
        border: 1px solid #dbe2ea;
        border-radius: 12px;
        background: rgba(248, 250, 252, 0.98);
        box-shadow: 0 10px 24px rgba(15, 23, 42, 0.08);
    }

    .ft-page-builder__hint.is-open .ft-page-builder__hint-body {
        display: block;
    }

    .ft-page-builder__hint--block .ft-page-builder__hint-body {
        margin-top: 0;
    }

    .ft-page-builder__field-error {
        color: #dc2626;
    }

    .ft-page-builder__subpanel {
        margin: 10px 0;
        padding: 10px;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        background: #fff;
    }

    .ft-page-builder__subpanel-title {
        margin: 0 0 2px;
        font-size: 12px;
        font-weight: 700;
        color: #0f172a;
    }

    .ft-page-builder__subpanel-desc {
        display: none;
    }

    .ft-page-builder__field-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 10px;
    }

    .ft-page-builder__list-editor {
        display: grid;
        gap: 10px;
    }

    .ft-page-builder__list-empty {
        padding: 12px 14px;
        border: 1px dashed #cbd5e1;
        border-radius: 12px;
        background: #f8fafc;
        color: #64748b;
        font-size: 12px;
        line-height: 1.7;
    }

    .ft-page-builder__list-card,
    .ft-page-builder__nav-card,
    .ft-page-builder__nav-child {
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        background: #fff;
    }

    .ft-page-builder__list-card,
    .ft-page-builder__nav-card {
        padding: 12px;
    }

    .ft-page-builder__nav-child {
        padding: 10px;
    }

    .ft-page-builder__list-card-head,
    .ft-page-builder__nav-card-head,
    .ft-page-builder__nav-children-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
        margin-bottom: 10px;
    }

    .ft-page-builder__list-card-head-actions,
    .ft-page-builder__nav-card-head-actions {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        flex-wrap: wrap;
    }

    .ft-page-builder__list-card-title,
    .ft-page-builder__nav-card-title,
    .ft-page-builder__nav-children-title {
        font-size: 12px;
        font-weight: 700;
        color: #0f172a;
    }

    .ft-page-builder__list-card-grid,
    .ft-page-builder__nav-card-grid,
    .ft-page-builder__nav-child-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 10px;
    }

    .ft-page-builder__list-actions,
    .ft-page-builder__nav-actions {
        display: flex;
        justify-content: flex-start;
        gap: 8px;
        margin-top: 10px;
        flex-wrap: wrap;
    }

    .ft-page-builder__nav-children {
        margin-top: 10px;
        padding-top: 10px;
        border-top: 1px dashed #e2e8f0;
        display: grid;
        gap: 8px;
    }

    .ft-page-builder__raw-editor {
        margin-top: 10px;
    }

    .ft-page-builder__raw-editor summary {
        cursor: pointer;
        color: #64748b;
        font-size: 12px;
        user-select: none;
    }

    .ft-page-builder__raw-editor-body {
        margin-top: 8px;
    }

    .ft-page-builder__platform-selector {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .ft-page-builder__platform-chip {
        border: 1px solid #dbe2ea;
        border-radius: 999px;
        background: #fff;
        color: #64748b;
        font-size: 12px;
        line-height: 1;
        padding: 10px 12px;
        cursor: pointer;
        transition: all .2s ease;
    }

    .ft-page-builder__platform-chip:hover {
        border-color: #bfdbfe;
        color: #1d4ed8;
        background: #eff6ff;
    }

    .ft-page-builder__platform-chip.is-active {
        border-color: #2563eb;
        color: #ffffff;
        background: #2563eb;
        box-shadow: 0 10px 20px rgba(37, 99, 235, 0.18);
    }

    .ft-page-builder__field-preset-bar {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 10px;
    }

    .ft-page-builder__list-card-head-actions .btn[disabled],
    .ft-page-builder__nav-card-head-actions .btn[disabled] {
        opacity: 0.45;
        cursor: not-allowed;
        pointer-events: none;
    }

    .ft-page-builder__inspector-panel .ft-page-builder__field:last-child,
    .ft-page-builder__inspector-panel .ft-page-builder__field.ft-page-form__full:last-child {
        margin-bottom: 0;
    }

    .ft-page-builder__inspector-panel textarea#builderNodeProps,
    .ft-page-builder__inspector-panel textarea#builderNodeStyle,
    .ft-page-builder__inspector-panel textarea#builderTabletStyle,
    .ft-page-builder__inspector-panel textarea#builderMobileStyle {
        min-height: 132px;
    }

    .ft-page-builder__live-preview-wrap {
        margin-top: 2px;
    }

    .ft-page-builder__live-preview-note {
        margin-top: 12px;
        color: #94a3b8;
        font-size: 12px;
        line-height: 1.7;
        overflow-wrap: anywhere;
    }

    .ft-page-builder__device-switch {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }

    .ft-page-builder__device-switch button {
        min-width: 64px;
        border-radius: 999px;
    }

    .ft-page-builder__device-meta {
        color: #64748b;
        font-size: 12px;
        line-height: 1.7;
    }

    .ft-page-builder__live-preview-shell {
        border-top: 1px solid #eef2f7;
        background: linear-gradient(180deg, #f8fafc 0%, #f1f5f9 100%);
    }

    .ft-page-builder__live-preview-toolbar {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 12px;
        padding: 8px 10px;
    }

    .ft-page-builder__live-preview-wrap .ft-page-builder__device-switch {
        justify-content: flex-end;
    }

    .ft-page-builder__live-preview-body {
        padding: 10px;
        min-height: 180px;
    }

    .ft-page-builder__live-preview-stage {
        display: flex;
        justify-content: center;
        width: 100%;
        overflow: auto;
    }

    .ft-page-builder__live-preview-viewport {
        width: 100%;
        min-height: 180px;
        transition: width .25s ease;
    }

    .ft-page-builder__live-preview-viewport--desktop {
        width: 100%;
        max-width: 100%;
    }

    .ft-page-builder__live-preview-viewport--tablet {
        width: 834px;
        max-width: 100%;
    }

    .ft-page-builder__live-preview-viewport--mobile {
        width: 390px;
        max-width: 100%;
    }

    .ft-page-builder__live-empty {
        padding: 24px 14px;
        border: 1px dashed #cbd5e1;
        border-radius: 12px;
        background: rgba(255, 255, 255, 0.9);
        text-align: center;
        color: #64748b;
        line-height: 1.6;
    }

    .ft-page-builder-preview {
        position: relative;
        color: #0f172a;
    }

    .ft-page-builder-preview .mx-page-root {
        position: relative;
    }

    .ft-page-builder-preview section {
        margin-bottom: 16px;
    }

    .ft-page-builder-preview .mx-page-section__inner {
        width: min(1180px, calc(100% - 40px));
        margin: 0 auto;
    }

    .ft-page-builder-preview .mx-page-row {
        display: grid;
        grid-template-columns: repeat(12, minmax(0, 1fr));
        gap: 16px;
    }

    .ft-page-builder-preview .mx-page-col {
        min-width: 0;
    }

    .ft-page-builder-preview .mx-page-col--1 { grid-column: span 1; }
    .ft-page-builder-preview .mx-page-col--2 { grid-column: span 2; }
    .ft-page-builder-preview .mx-page-col--3 { grid-column: span 3; }
    .ft-page-builder-preview .mx-page-col--4 { grid-column: span 4; }
    .ft-page-builder-preview .mx-page-col--5 { grid-column: span 5; }
    .ft-page-builder-preview .mx-page-col--6 { grid-column: span 6; }
    .ft-page-builder-preview .mx-page-col--7 { grid-column: span 7; }
    .ft-page-builder-preview .mx-page-col--8 { grid-column: span 8; }
    .ft-page-builder-preview .mx-page-col--9 { grid-column: span 9; }
    .ft-page-builder-preview .mx-page-col--10 { grid-column: span 10; }
    .ft-page-builder-preview .mx-page-col--11 { grid-column: span 11; }
    .ft-page-builder-preview .mx-page-col--12 { grid-column: span 12; }

    .ft-page-builder-preview .mx-page-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 12px 20px;
        min-height: 44px;
        border-radius: 999px;
        background: #2563eb;
        color: #fff;
        border: 1px solid transparent;
        text-decoration: none;
        font-weight: 700;
        width: fit-content;
        transition: background .22s ease, color .22s ease, border-color .22s ease, box-shadow .22s ease, transform .22s ease;
        --mx-current-background: #2563eb;
        --mx-current-color: #ffffff;
        --mx-current-border-color: transparent;
    }

    .ft-page-builder-preview .mx-page-button--center {
        display: flex;
        margin-left: auto;
        margin-right: auto;
    }

    .ft-page-builder-preview .mx-page-button--right {
        display: flex;
        margin-left: auto;
    }

    .ft-page-builder-preview .mx-page-button--full {
        display: flex;
        width: 100%;
    }

    .ft-page-builder-preview .mx-page-button--outline {
        background: transparent;
        color: #2563eb;
        border-color: var(--mx-current-border-color, currentColor);
        --mx-current-background: transparent;
        --mx-current-color: #2563eb;
        --mx-current-border-color: currentColor;
    }

    .ft-page-builder-preview .mx-page-button--ghost {
        background: rgba(37, 99, 235, 0.08);
        color: #2563eb;
        --mx-current-background: rgba(37, 99, 235, 0.08);
        --mx-current-color: #2563eb;
    }

    .ft-page-builder-preview .mx-page-button:hover {
        background: var(--mx-hover-background, var(--mx-current-background));
        color: var(--mx-hover-color, var(--mx-current-color));
        border-color: var(--mx-hover-border-color, var(--mx-current-border-color));
        box-shadow: var(--mx-hover-box-shadow, var(--mx-current-box-shadow, none));
        transform: var(--mx-hover-transform, none);
    }

    .ft-page-builder-preview .mx-page-image {
        display: block;
        max-width: 100%;
        transition: box-shadow .22s ease, transform .22s ease;
    }

    .ft-page-builder-preview .mx-page-image--center {
        margin-left: auto;
        margin-right: auto;
    }

    .ft-page-builder-preview .mx-page-image--right {
        margin-left: auto;
    }

    .ft-page-builder-preview .mx-page-image:hover {
        box-shadow: var(--mx-hover-box-shadow, var(--mx-current-box-shadow, none));
        transform: var(--mx-hover-transform, none);
    }

    .ft-page-builder-preview .mx-page-carousel {
        position: relative;
        overflow: hidden;
        min-height: 280px;
        border-radius: 22px;
        background: #0f172a;
        color: #fff;
    }

    .ft-page-builder-preview .mx-page-carousel__media img {
        display: block;
        width: 100%;
        min-height: 280px;
        object-fit: cover;
    }

    .ft-page-builder-preview .mx-page-carousel__overlay {
        position: absolute;
        inset: 0;
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        gap: 10px;
        padding: 28px;
        background: linear-gradient(180deg, rgba(15, 23, 42, 0.08) 0%, rgba(15, 23, 42, 0.8) 72%, rgba(15, 23, 42, 0.92) 100%);
    }

    .ft-page-builder-preview .mx-page-carousel__meta {
        font-size: 11px;
        font-weight: 700;
        letter-spacing: .08em;
        text-transform: uppercase;
        opacity: .82;
    }

    .ft-page-builder-preview .mx-page-carousel__title {
        margin: 0;
        font-size: 28px;
        line-height: 1.15;
    }

    .ft-page-builder-preview .mx-page-carousel__desc {
        margin: 0;
        max-width: 560px;
        line-height: 1.7;
        opacity: .92;
    }

    .ft-page-builder-preview .mx-page-carousel__button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: fit-content;
        min-height: 38px;
        padding: 0 14px;
        border-radius: 999px;
        background: #fff;
        color: #0f172a;
        text-decoration: none;
        font-size: 12px;
        font-weight: 700;
    }

    .ft-page-builder-preview .mx-page-carousel__dots {
        position: absolute;
        left: 28px;
        right: 28px;
        bottom: 14px;
        display: flex;
        gap: 8px;
    }

    .ft-page-builder-preview .mx-page-carousel__dot {
        flex: 1;
        height: 6px;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.28);
    }

    .ft-page-builder-preview .mx-page-carousel__dot.is-active {
        background: #ffffff;
    }

    .ft-page-builder-preview .mx-page-video {
        display: grid;
        gap: 10px;
    }

    .ft-page-builder-preview .mx-page-video__title {
        font-size: 16px;
        font-weight: 700;
        color: #0f172a;
    }

    .ft-page-builder-preview .mx-page-video__viewport {
        position: relative;
        overflow: hidden;
        border-radius: 20px;
        background: #0f172a;
    }

    .ft-page-builder-preview .mx-page-video__frame {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        border: 0;
        display: block;
    }

    .ft-page-builder-preview .mx-page-gallery,
    .ft-page-builder-preview .mx-page-faq,
    .ft-page-builder-preview .mx-page-stats,
    .ft-page-builder-preview .mx-page-cta {
        display: grid;
        gap: 14px;
    }

    .ft-page-builder-preview .mx-page-gallery__head,
    .ft-page-builder-preview .mx-page-faq__head,
    .ft-page-builder-preview .mx-page-stats__head {
        display: grid;
        gap: 6px;
    }

    .ft-page-builder-preview .mx-page-gallery__title,
    .ft-page-builder-preview .mx-page-faq__title,
    .ft-page-builder-preview .mx-page-stats__title,
    .ft-page-builder-preview .mx-page-cta__title {
        margin: 0;
        color: #0f172a;
        line-height: 1.2;
    }

    .ft-page-builder-preview .mx-page-gallery__subtitle,
    .ft-page-builder-preview .mx-page-faq__intro,
    .ft-page-builder-preview .mx-page-stats__intro,
    .ft-page-builder-preview .mx-page-cta__desc {
        margin: 0;
        color: #64748b;
        line-height: 1.8;
    }

    .ft-page-builder-preview .mx-page-gallery__grid,
    .ft-page-builder-preview .mx-page-stats__grid,
    .ft-page-builder-preview .mx-page-faq__list {
        display: grid;
        gap: 14px;
    }

    .ft-page-builder-preview .mx-page-gallery__card,
    .ft-page-builder-preview .mx-page-faq__item,
    .ft-page-builder-preview .mx-page-stats__item {
        border-radius: 18px;
        border: 1px solid #e2e8f0;
        background: #ffffff;
        box-shadow: 0 12px 28px rgba(15, 23, 42, 0.05);
    }

    .ft-page-builder-preview .mx-page-gallery__card {
        overflow: hidden;
    }

    .ft-page-builder-preview .mx-page-gallery__card-link {
        display: block;
        color: inherit;
        text-decoration: none;
    }

    .ft-page-builder-preview .mx-page-gallery__media {
        aspect-ratio: 4 / 3;
        background: #e2e8f0;
    }

    .ft-page-builder-preview .mx-page-gallery__media img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .ft-page-builder-preview .mx-page-gallery__caption {
        padding: 12px 14px 14px;
        font-size: 12px;
        font-weight: 700;
        color: #0f172a;
    }

    .ft-page-builder-preview .mx-page-faq__list--cols-2 {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .ft-page-builder-preview .mx-page-faq__item,
    .ft-page-builder-preview .mx-page-stats__item {
        padding: 18px;
    }

    .ft-page-builder-preview .mx-page-faq__question {
        margin: 0 0 8px;
        font-size: 15px;
        color: #0f172a;
    }

    .ft-page-builder-preview .mx-page-faq__answer {
        color: #475569;
        font-size: 12px;
        line-height: 1.8;
    }

    .ft-page-builder-preview .mx-page-stats__label {
        color: #64748b;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: .03em;
    }

    .ft-page-builder-preview .mx-page-stats__value {
        margin-top: 8px;
        font-size: 30px;
        line-height: 1;
        font-weight: 800;
        color: #0f172a;
    }

    .ft-page-builder-preview .mx-page-stats__value span {
        margin-left: 4px;
        font-size: .44em;
        color: #2563eb;
    }

    .ft-page-builder-preview .mx-page-stats__desc {
        margin-top: 8px;
        color: #64748b;
        font-size: 12px;
        line-height: 1.7;
    }

    .ft-page-builder-preview .mx-page-cta {
        padding: 26px;
        border-radius: 22px;
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        color: #ffffff;
    }

    .ft-page-builder-preview .mx-page-cta--center {
        text-align: center;
        justify-items: center;
    }

    .ft-page-builder-preview .mx-page-cta__body {
        display: grid;
        gap: 10px;
        max-width: 640px;
    }

    .ft-page-builder-preview .mx-page-cta__eyebrow {
        color: rgba(255, 255, 255, 0.72);
        font-size: 11px;
        font-weight: 700;
        letter-spacing: .12em;
        text-transform: uppercase;
    }

    .ft-page-builder-preview .mx-page-cta__title,
    .ft-page-builder-preview .mx-page-cta__desc {
        color: #ffffff;
    }

    .ft-page-builder-preview .mx-page-cta__actions {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 4px;
    }

    .ft-page-builder-preview .mx-page-cta--center .mx-page-cta__actions {
        justify-content: center;
    }

    .ft-page-builder-preview .mx-page-cta__actions--center {
        justify-content: center;
    }

    .ft-page-builder-preview .mx-page-cta__actions--right {
        justify-content: flex-end !important;
    }

    .ft-page-builder-preview .mx-page-cta__button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: var(--mx-cta-button-min-height, 38px);
        padding: 0 14px;
        border-radius: 999px;
        background: #ffffff;
        color: #0f172a;
        border: 1px solid transparent;
        text-decoration: none;
        font-size: 12px;
        font-weight: 700;
        transition: background .22s ease, color .22s ease, border-color .22s ease, box-shadow .22s ease, transform .22s ease;
        --mx-current-background: #ffffff;
        --mx-current-color: #0f172a;
        --mx-current-border-color: transparent;
    }

    .ft-page-builder-preview .mx-page-cta__button--ghost {
        background: rgba(255, 255, 255, 0.08);
        color: #ffffff;
        border: 1px solid rgba(255, 255, 255, 0.22);
        --mx-current-background: rgba(255, 255, 255, 0.08);
        --mx-current-color: #ffffff;
        --mx-current-border-color: rgba(255, 255, 255, 0.22);
    }

    .ft-page-builder-preview .mx-page-cta__button--outline {
        background: transparent;
        color: #ffffff;
        border-color: rgba(255, 255, 255, 0.5);
        --mx-current-background: transparent;
        --mx-current-color: #ffffff;
        --mx-current-border-color: rgba(255, 255, 255, 0.5);
    }

    .ft-page-builder-preview .mx-page-cta__button:hover {
        background: var(--mx-hover-background, var(--mx-current-background));
        color: var(--mx-hover-color, var(--mx-current-color));
        border-color: var(--mx-hover-border-color, var(--mx-current-border-color));
        box-shadow: var(--mx-hover-box-shadow, var(--mx-current-box-shadow, none));
        transform: var(--mx-hover-transform, none);
    }

    @keyframes mx-page-motion-fade-up {
        from { opacity: 0; transform: translate3d(0, 18px, 0); }
        to { opacity: 1; transform: translate3d(0, 0, 0); }
    }

    @keyframes mx-page-motion-fade-in {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @keyframes mx-page-motion-zoom-in {
        from { opacity: 0; transform: scale(.96); }
        to { opacity: 1; transform: scale(1); }
    }

    .ft-page-builder-preview .mx-page-nav {
        display: grid;
        gap: 12px;
    }

    .ft-page-builder-preview .mx-page-nav__title {
        font-size: 12px;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: .08em;
    }

    .ft-page-builder-preview .mx-page-nav__list {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .ft-page-builder-preview .mx-page-nav--vertical .mx-page-nav__list {
        display: grid;
    }

    .ft-page-builder-preview .mx-page-nav__link {
        display: inline-flex;
        align-items: center;
        min-height: 34px;
        padding: 0 12px;
        border-radius: 999px;
        background: #f8fafc;
        color: #0f172a;
        text-decoration: none;
        font-size: 12px;
        font-weight: 600;
    }

    .ft-page-builder-preview .mx-page-nav__link.is-active {
        background: #2563eb;
        color: #ffffff;
    }

    .ft-page-builder-preview .mx-page-nav__item {
        position: relative;
    }

    .ft-page-builder-preview .mx-page-nav__link.has-children::after {
        content: "v";
        margin-left: 6px;
        font-size: 10px;
        opacity: .7;
    }

    .ft-page-builder-preview .mx-page-nav__submenu {
        display: grid;
        gap: 6px;
        min-width: 160px;
        margin-top: 8px;
        padding: 10px;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        background: #ffffff;
        box-shadow: 0 12px 30px rgba(15, 23, 42, 0.12);
    }

    .ft-page-builder-preview .mx-page-nav__submenu-link {
        display: block;
        padding: 8px 10px;
        border-radius: 10px;
        color: #334155;
        text-decoration: none;
        font-size: 12px;
        font-weight: 600;
    }

    .ft-page-builder-preview .mx-page-nav__submenu-link.is-active,
    .ft-page-builder-preview .mx-page-nav__submenu-link:hover {
        background: #eff6ff;
        color: #1d4ed8;
    }

    .ft-page-builder-preview .mx-page-nav__cta {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 36px;
        padding: 0 14px;
        border-radius: 999px;
        background: #0f172a;
        color: #ffffff;
        text-decoration: none;
        font-size: 12px;
        font-weight: 700;
    }

    .ft-page-builder-preview .mx-page-qrcode__text,
    .ft-page-builder-preview .mx-page-qrcode__value,
    .ft-page-builder-preview .mx-page-login-box__label,
    .ft-page-builder-preview .mx-page-login-box__profile-text {
        color: #64748b;
        line-height: 1.7;
    }

    .ft-page-builder-preview .mx-page-sidebar {
        position: absolute;
        top: var(--mx-sidebar-offset, 120px);
        z-index: 30;
        display: grid;
        gap: 10px;
        width: 88px;
    }

    .ft-page-builder-preview .mx-page-sidebar[data-sidebar-position="left"] {
        left: 12px;
    }

    .ft-page-builder-preview .mx-page-sidebar[data-sidebar-position="right"] {
        right: 12px;
    }

    .ft-page-builder-preview .mx-page-sidebar__title,
    .ft-page-builder-preview .mx-page-sidebar__link,
    .ft-page-builder-preview .mx-page-sidebar__backtop {
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        background: #ffffff;
        box-shadow: 0 12px 30px rgba(15, 23, 42, 0.12);
    }

    .ft-page-builder-preview .mx-page-sidebar__title {
        padding: 10px 8px;
        text-align: center;
        font-size: 12px;
        font-weight: 700;
        color: #0f172a;
    }

    .ft-page-builder-preview .mx-page-sidebar__list {
        display: grid;
        gap: 10px;
    }

    .ft-page-builder-preview .mx-page-sidebar__link,
    .ft-page-builder-preview .mx-page-sidebar__backtop {
        display: grid;
        justify-items: center;
        gap: 6px;
        width: 88px;
        min-height: 74px;
        padding: 10px 8px;
        text-align: center;
        color: var(--mx-sidebar-item-color, #334155);
        text-decoration: none;
        font-size: 12px;
        background: var(--mx-sidebar-item-bg, #ffffff);
        border-color: var(--mx-sidebar-item-border, #e2e8f0);
        transition: box-shadow .2s ease, transform .2s ease, border-color .2s ease;
        appearance: none;
    }

    .ft-page-builder-preview .mx-page-sidebar__icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        border-radius: 12px;
        background: color-mix(in srgb, var(--mx-sidebar-item-color, #2563eb) 12%, white);
        color: var(--mx-sidebar-item-color, #2563eb);
        font-weight: 700;
        flex: 0 0 auto;
    }

    .ft-page-builder-preview .mx-page-sidebar__icon--image,
    .ft-page-builder-preview .mx-page-sidebar__icon--svg {
        padding: 0;
        overflow: hidden;
    }

    .ft-page-builder-preview .mx-page-sidebar__icon--image img,
    .ft-page-builder-preview .mx-page-sidebar__icon--svg svg {
        display: block;
        width: 20px;
        height: 20px;
        object-fit: contain;
    }

    .ft-page-builder-preview .mx-page-sidebar__content {
        display: grid;
        gap: 2px;
        width: 100%;
    }

    .ft-page-builder-preview .mx-page-sidebar__text {
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
        font-weight: 600;
    }

    .ft-page-builder-preview .mx-page-sidebar__desc {
        display: none;
    }

    .ft-page-builder-preview .mx-page-sidebar__link:hover,
    .ft-page-builder-preview .mx-page-sidebar__trigger[aria-expanded="true"],
    .ft-page-builder-preview .mx-page-sidebar__backtop:hover {
        box-shadow: 0 18px 36px rgba(15, 23, 42, 0.16);
        transform: translateY(-1px);
    }

    .ft-page-builder-preview .mx-page-sidebar__backtop {
        cursor: pointer;
    }

    .ft-page-builder-preview .mx-page-sidebar__panels {
        position: absolute;
        inset: 0;
        pointer-events: none;
    }

    .ft-page-builder-preview .mx-page-sidebar__panel {
        position: absolute;
        top: var(--mx-sidebar-panel-top, 0px);
        width: 280px;
        pointer-events: auto;
    }

    .ft-page-builder-preview .mx-page-sidebar[data-sidebar-position="right"] .mx-page-sidebar__panel {
        right: calc(100% + 14px);
    }

    .ft-page-builder-preview .mx-page-sidebar[data-sidebar-position="left"] .mx-page-sidebar__panel {
        left: calc(100% + 14px);
    }

    .ft-page-builder-preview .mx-page-sidebar__panel-card {
        position: relative;
        display: grid;
        gap: 12px;
        padding: 18px;
        border-radius: 22px;
        border: 1px solid #e2e8f0;
        background: #ffffff;
        box-shadow: 0 22px 44px rgba(15, 23, 42, 0.16);
    }

    .ft-page-builder-preview .mx-page-sidebar__panel-close {
        position: absolute;
        top: 10px;
        right: 10px;
        width: 28px;
        height: 28px;
        border: 0;
        border-radius: 999px;
        background: #f1f5f9;
        color: #0f172a;
        cursor: pointer;
    }

    .ft-page-builder-preview .mx-page-sidebar__panel-title {
        padding-right: 28px;
        font-size: 15px;
        font-weight: 700;
        color: #0f172a;
    }

    .ft-page-builder-preview .mx-page-sidebar__panel-qrcode,
    .ft-page-builder-preview .mx-page-sidebar__panel-media {
        display: grid;
        justify-items: center;
    }

    .ft-page-builder-preview .mx-page-sidebar__panel-qrcode img,
    .ft-page-builder-preview .mx-page-sidebar__panel-media img {
        display: block;
        max-width: 160px;
        width: 100%;
        border-radius: 16px;
    }

    .ft-page-builder-preview .mx-page-sidebar__panel-text,
    .ft-page-builder-preview .mx-page-sidebar__panel-value {
        color: #64748b;
        line-height: 1.7;
        word-break: break-word;
    }

    .ft-page-builder-preview .mx-page-sidebar__panel-rich {
        color: #334155;
        line-height: 1.8;
        word-break: break-word;
    }

    .ft-page-builder-preview .mx-page-sidebar__panel-rich > :first-child {
        margin-top: 0;
    }

    .ft-page-builder-preview .mx-page-sidebar__panel-rich > :last-child {
        margin-bottom: 0;
    }

    .ft-page-builder-preview .mx-page-qrcode {
        display: grid;
        justify-items: center;
        gap: 10px;
    }

    .ft-page-builder-preview .mx-page-qrcode__image img {
        display: block;
        width: 120px;
        max-width: 100%;
        height: auto;
        padding: 8px;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        background: #fff;
    }

    .ft-page-builder-preview .mx-page-login-box {
        display: inline-flex;
        align-items: center;
        gap: 10px;
    }

    .ft-page-builder-preview .mx-page-login-box__label {
        font-size: 12px;
        font-weight: 700;
        color: #64748b;
    }

    .ft-page-builder-preview .mx-page-login-box__profile {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        color: #0f172a;
        text-decoration: none;
        font-weight: 700;
    }

    .ft-page-builder-preview .mx-page-login-box__button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 38px;
        padding: 0 16px;
        border-radius: 999px;
        background: #2563eb;
        color: #fff;
        text-decoration: none;
        font-weight: 700;
    }

    .ft-page-builder-preview .mx-page-login-box__avatar {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        overflow: hidden;
        background: #dbeafe;
        color: #1d4ed8;
        font-size: 13px;
        font-weight: 700;
    }

    .ft-page-builder-preview .mx-page-login-box__avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .ft-page-builder-preview .mx-page-model-list {
        padding: 20px;
        border-radius: 18px;
        border: 1px solid #dbeafe;
        background: linear-gradient(135deg, #eff6ff 0%, #f8fbff 100%);
    }

    .ft-page-builder-preview .mx-page-model-detail {
        padding: 20px;
        border-radius: 18px;
        border: 1px solid #e5e7eb;
        background: #fff;
        box-shadow: 0 16px 36px rgba(15, 23, 42, 0.06);
    }

    .ft-page-builder-preview .mx-page-placeholder-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 16px;
        color: #1e3a8a;
    }

    .ft-page-builder-preview .mx-page-placeholder-head span {
        color: #64748b;
        font-size: 12px;
    }

    .ft-page-builder-preview .mx-page-placeholder-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 14px;
    }

    .ft-page-builder-preview .mx-page-placeholder-card {
        padding: 16px;
        border: 1px solid #dbe2ea;
        border-radius: 16px;
        background: rgba(255, 255, 255, 0.94);
        box-shadow: 0 10px 24px rgba(15, 23, 42, 0.05);
    }

    .ft-page-builder-preview .mx-page-placeholder-card h4 {
        margin: 0 0 8px;
        font-size: 14px;
        color: #0f172a;
    }

    .ft-page-builder-preview .mx-page-placeholder-card p {
        margin: 0;
        line-height: 1.7;
        color: #64748b;
        font-size: 12px;
    }

    .ft-page-builder-preview .mx-page-detail-card {
        display: grid;
        gap: 16px;
    }

    .ft-page-builder-preview .mx-page-detail-card__title {
        margin: 0 0 12px;
        font-size: 22px;
        line-height: 1.25;
        color: #0f172a;
    }

    .ft-page-builder-preview .mx-page-detail-card__summary,
    .ft-page-builder-preview .mx-page-detail-card__body {
        margin: 0 0 12px;
        line-height: 1.8;
        color: #475569;
        white-space: pre-wrap;
    }

    .ft-page-builder-preview .mx-page-detail-card__footer {
        color: #94a3b8;
        font-size: 12px;
    }

    .ft-page-builder-preview.is-device-tablet .mx-page-placeholder-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .ft-page-builder-preview.is-device-tablet .mx-page-detail-card--media {
        grid-template-columns: 1fr;
    }

    .ft-page-builder-preview.is-device-mobile .mx-page-row,
    .ft-page-builder-preview.is-device-mobile .mx-page-placeholder-grid,
    .ft-page-builder-preview.is-device-mobile .mx-page-detail-card--media,
    .ft-page-builder-preview.is-device-mobile .mx-page-placeholder-card--list {
        grid-template-columns: 1fr;
    }

    .ft-page-builder-preview.is-device-mobile .mx-page-col {
        grid-column: span 1 !important;
    }

    .ft-page-builder-preview.is-device-mobile .mx-page-placeholder-card--list .mx-page-placeholder-media {
        margin: -18px -18px 14px;
        height: auto;
    }

    .ft-page-builder-preview.is-device-mobile .mx-page-placeholder-card--list .mx-page-placeholder-media img {
        height: 220px;
        min-height: 0;
    }

    .ft-page-builder-preview.is-device-mobile .mx-page-section__inner {
        width: calc(100% - 28px);
    }

    .ft-page-builder-preview.is-device-mobile .mx-page-sidebar {
        top: auto;
        right: 12px;
        bottom: 12px;
        left: 12px;
        width: auto;
        max-width: calc(100% - 24px);
    }

    .ft-page-builder-preview.is-device-mobile .mx-page-sidebar__title {
        display: none;
    }

    .ft-page-builder-preview.is-device-mobile .mx-page-sidebar__list {
        display: flex;
        flex-wrap: wrap;
        justify-content: flex-end;
        gap: 8px;
    }

    .ft-page-builder-preview.is-device-mobile .mx-page-sidebar__link,
    .ft-page-builder-preview.is-device-mobile .mx-page-sidebar__backtop {
        justify-content: center;
        width: 52px;
        min-height: 52px;
        padding: 8px;
    }

    .ft-page-builder-preview.is-device-mobile .mx-page-sidebar__content {
        display: none;
    }

    .ft-page-builder-preview.is-device-mobile .mx-page-sidebar__panel {
        position: fixed;
        right: 12px;
        bottom: 76px;
        left: 12px;
        top: auto;
        width: auto;
    }

    .ft-page-builder__live-preview-shell {
        border-top: 1px solid #e2e8f0;
        background: linear-gradient(180deg, #f8fafc 0%, #eef2f7 100%);
    }

    .ft-page-builder__live-preview-toolbar {
        padding: 10px 12px;
    }

    .ft-page-builder__live-preview-body {
        padding: 14px;
        min-height: 220px;
    }

    .ft-page-builder-preview {
        color: #0f172a;
        background: #f8fafc;
    }

    .ft-page-builder-preview section {
        margin-bottom: 0;
    }

    .ft-page-builder-preview .mx-page-html {
        width: 100%;
    }

    .ft-page-builder-preview .mx-page-section__inner {
        width: min(1180px, calc(100% - 40px));
        margin: 0 auto;
    }

    .ft-page-builder-preview .mx-page-row {
        gap: 20px;
    }

    .ft-page-builder-preview .mx-page-carousel {
        min-height: 360px;
        border-radius: 24px;
    }

    .ft-page-builder-preview .mx-page-carousel__media img {
        min-height: 360px;
    }

    .ft-page-builder-preview .mx-page-carousel__overlay {
        gap: 14px;
        padding: 40px;
        background: linear-gradient(180deg, rgba(15, 23, 42, 0.06) 0%, rgba(15, 23, 42, 0.78) 68%, rgba(15, 23, 42, 0.92) 100%);
    }

    .ft-page-builder-preview .mx-page-carousel__meta {
        font-size: 12px;
    }

    .ft-page-builder-preview .mx-page-carousel__title {
        max-width: 720px;
        font-size: 38px;
    }

    .ft-page-builder-preview .mx-page-carousel__desc {
        max-width: 680px;
        line-height: 1.8;
    }

    .ft-page-builder-preview .mx-page-carousel__button {
        min-height: 44px;
        padding: 0 18px;
        font-size: inherit;
    }

    .ft-page-builder-preview .mx-page-carousel__dots {
        left: 40px;
        right: 40px;
        bottom: 20px;
    }

    .ft-page-builder-preview .mx-page-video {
        gap: 12px;
    }

    .ft-page-builder-preview .mx-page-video__title {
        font-size: 20px;
    }

    .ft-page-builder-preview .mx-page-video__viewport {
        border-radius: 22px;
    }

    .ft-page-builder-preview .mx-page-gallery,
    .ft-page-builder-preview .mx-page-faq,
    .ft-page-builder-preview .mx-page-stats,
    .ft-page-builder-preview .mx-page-cta {
        gap: 18px;
    }

    .ft-page-builder-preview .mx-page-gallery__head,
    .ft-page-builder-preview .mx-page-faq__head,
    .ft-page-builder-preview .mx-page-stats__head {
        gap: 8px;
    }

    .ft-page-builder-preview .mx-page-gallery__grid,
    .ft-page-builder-preview .mx-page-stats__grid,
    .ft-page-builder-preview .mx-page-faq__list {
        gap: 18px;
    }

    .ft-page-builder-preview .mx-page-gallery__card {
        box-shadow: 0 14px 32px rgba(15, 23, 42, 0.06);
    }

    .ft-page-builder-preview .mx-page-gallery__caption {
        padding: 14px 16px 16px;
        font-size: inherit;
    }

    .ft-page-builder-preview .mx-page-faq__item {
        padding: 22px 24px;
        box-shadow: 0 14px 32px rgba(15, 23, 42, 0.05);
    }

    .ft-page-builder-preview .mx-page-faq__question {
        margin: 0 0 10px;
        font-size: 18px;
    }

    .ft-page-builder-preview .mx-page-faq__answer {
        font-size: inherit;
        line-height: 1.9;
    }

    .ft-page-builder-preview .mx-page-stats__grid {
        gap: 16px;
    }

    .ft-page-builder-preview .mx-page-stats__item {
        padding: 24px;
        border-radius: 22px;
        background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
        box-shadow: 0 14px 32px rgba(15, 23, 42, 0.05);
    }

    .ft-page-builder-preview .mx-page-stats__label {
        font-size: 13px;
    }

    .ft-page-builder-preview .mx-page-stats__value {
        margin-top: 10px;
        font-size: 40px;
    }

    .ft-page-builder-preview .mx-page-stats__desc {
        margin-top: 10px;
        font-size: inherit;
        line-height: 1.7;
    }

    .ft-page-builder-preview .mx-page-cta {
        padding: 32px;
        border-radius: 24px;
    }

    .ft-page-builder-preview .mx-page-cta__body {
        gap: 12px;
        max-width: 760px;
    }

    .ft-page-builder-preview .mx-page-cta__eyebrow {
        font-size: 12px;
    }

    .ft-page-builder-preview .mx-page-cta__actions {
        gap: 12px;
        margin-top: 6px;
    }

    .ft-page-builder-preview .mx-page-cta__button {
        min-height: var(--mx-cta-button-min-height, 46px);
        padding: 0 18px;
        font-size: inherit;
    }

    .ft-page-builder-preview .mx-page-nav {
        gap: 14px;
    }

    .ft-page-builder-preview .mx-page-nav__title {
        font-size: 13px;
    }

    .ft-page-builder-preview .mx-page-nav__list {
        gap: 10px;
    }

    .ft-page-builder-preview .mx-page-nav__link {
        min-height: 40px;
        padding: 0 16px;
        font-size: inherit;
    }

    .ft-page-builder-preview .mx-page-nav__submenu {
        position: absolute;
        left: 0;
        top: calc(100% + 8px);
        z-index: 10;
        display: none;
        box-shadow: 0 16px 40px rgba(15, 23, 42, 0.14);
    }

    .ft-page-builder-preview .mx-page-nav__item:hover .mx-page-nav__submenu {
        display: grid;
        gap: 6px;
    }

    .ft-page-builder-preview .mx-page-nav__submenu-link {
        padding: 9px 12px;
        font-size: inherit;
    }

    .ft-page-builder-preview .mx-page-nav__cta {
        min-height: 40px;
        padding: 0 16px;
        font-size: inherit;
    }


    .ft-page-builder-preview .mx-page-qrcode {
        gap: 12px;
    }

    .ft-page-builder-preview .mx-page-qrcode__image img {
        width: 140px;
        border-radius: 18px;
    }

    .ft-page-builder-preview .mx-page-qrcode__title {
        font-size: 22px;
        font-weight: 700;
        color: #0f172a;
    }

    .ft-page-builder-preview .mx-page-login-box {
        gap: 12px;
    }

    .ft-page-builder-preview .mx-page-login-box__button {
        min-height: 42px;
        padding: 0 18px;
    }

    .ft-page-builder-preview .mx-page-login-box__avatar {
        width: 40px;
        height: 40px;
        font-size: 14px;
    }

    .ft-page-builder-preview .mx-page-model-list {
        padding: 24px;
    }

    .ft-page-builder-preview .mx-page-model-detail {
        padding: 24px;
    }

    .ft-page-builder-preview .mx-page-placeholder-head {
        margin-bottom: 18px;
    }

    .ft-page-builder-preview .mx-page-placeholder-grid {
        gap: 16px;
    }

    .ft-page-builder-preview .mx-page-placeholder-card {
        padding: 18px;
        box-shadow: 0 10px 24px rgba(15, 23, 42, 0.05);
        overflow: hidden;
    }

    .ft-page-builder-preview .mx-page-placeholder-card--list {
        display: grid;
        grid-template-columns: 180px minmax(0, 1fr);
        gap: 18px;
        align-items: start;
    }

    .ft-page-builder-preview .mx-page-placeholder-media {
        margin: -18px -18px 14px;
        overflow: hidden;
        background: #e2e8f0;
    }

    .ft-page-builder-preview .mx-page-placeholder-card--list .mx-page-placeholder-media {
        margin: -18px 0 -18px -18px;
        height: calc(100% + 36px);
    }

    .ft-page-builder-preview .mx-page-placeholder-media img {
        display: block;
        width: 100%;
        height: 220px;
        object-fit: cover;
    }

    .ft-page-builder-preview .mx-page-placeholder-card--list .mx-page-placeholder-media img {
        height: 100%;
        min-height: 160px;
    }

    .ft-page-builder-preview .mx-page-placeholder-card h4 {
        margin: 0 0 10px;
        font-size: 16px;
    }

    .ft-page-builder-preview .mx-page-placeholder-card p {
        font-size: inherit;
        line-height: 1.8;
    }

    .ft-page-builder-preview .mx-page-placeholder-footer {
        margin-top: 14px;
        color: #94a3b8;
        font-size: 12px;
    }

    .ft-page-builder-preview .mx-page-detail-card {
        gap: 20px;
    }

    .ft-page-builder-preview .mx-page-detail-card--media {
        grid-template-columns: minmax(280px, 420px) minmax(0, 1fr);
        align-items: start;
    }

    .ft-page-builder-preview .mx-page-detail-card__media {
        overflow: hidden;
        border-radius: 18px;
        background: #e2e8f0;
    }

    .ft-page-builder-preview .mx-page-detail-card__media img {
        display: block;
        width: 100%;
        height: auto;
        min-height: 260px;
        object-fit: cover;
    }

    .ft-page-builder-preview .mx-page-detail-card__title {
        margin: 0 0 14px;
        font-size: 30px;
        line-height: 1.2;
    }

    .ft-page-builder-preview .mx-page-detail-card__summary {
        margin: 0 0 16px;
        font-size: 15px;
        line-height: 1.9;
    }

    .ft-page-builder-preview .mx-page-detail-card__body {
        margin: 0;
        color: #334155;
        line-height: 1.9;
        white-space: pre-wrap;
    }

    .ft-page-builder-preview .mx-page-detail-card__footer {
        margin-top: 18px;
    }

    .ft-page-builder-preview.is-device-tablet .mx-page-placeholder-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .ft-page-builder-preview.is-device-tablet .mx-page-detail-card--media {
        grid-template-columns: 1fr;
    }

    .ft-page-builder-preview.is-device-tablet .mx-page-carousel__overlay {
        padding: 28px;
    }

    .ft-page-builder-preview.is-device-tablet .mx-page-carousel__title {
        font-size: 30px;
    }

    .ft-page-builder-preview.is-device-tablet .mx-page-gallery__grid,
    .ft-page-builder-preview.is-device-tablet .mx-page-stats__grid {
        grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
    }

    .ft-page-builder-preview.is-device-tablet .mx-page-faq__list--cols-2 {
        grid-template-columns: 1fr;
    }

    .ft-page-builder-preview.is-device-mobile .mx-page-section__inner {
        width: calc(100% - 28px);
    }

    .ft-page-builder-preview.is-device-mobile .mx-page-row,
    .ft-page-builder-preview.is-device-mobile .mx-page-placeholder-grid,
    .ft-page-builder-preview.is-device-mobile .mx-page-detail-card--media,
    .ft-page-builder-preview.is-device-mobile .mx-page-placeholder-card--list {
        grid-template-columns: 1fr;
    }

    .ft-page-builder-preview.is-device-mobile .mx-page-col {
        grid-column: span 1 !important;
    }

    .ft-page-builder-preview.is-device-mobile .mx-page-placeholder-card--list .mx-page-placeholder-media {
        margin: -18px -18px 14px;
        height: auto;
    }

    .ft-page-builder-preview.is-device-mobile .mx-page-placeholder-card--list .mx-page-placeholder-media img {
        height: 220px;
        min-height: 0;
    }

    .ft-page-builder-preview.is-device-mobile .mx-page-carousel {
        min-height: 280px;
    }

    .ft-page-builder-preview.is-device-mobile .mx-page-carousel__media img {
        min-height: 280px;
    }

    .ft-page-builder-preview.is-device-mobile .mx-page-carousel__overlay {
        padding: 22px 18px 48px;
    }

    .ft-page-builder-preview.is-device-mobile .mx-page-carousel__title {
        font-size: 24px;
    }

    .ft-page-builder-preview.is-device-mobile .mx-page-carousel__dots {
        left: 18px;
        right: 18px;
        bottom: 14px;
    }

    .ft-page-builder-preview.is-device-mobile .mx-page-gallery__grid,
    .ft-page-builder-preview.is-device-mobile .mx-page-stats__grid,
    .ft-page-builder-preview.is-device-mobile .mx-page-faq__list {
        grid-template-columns: 1fr !important;
    }

    .ft-page-builder-preview.is-device-mobile .mx-page-cta {
        padding: 24px 20px;
    }

    .ft-page-builder-preview.is-device-mobile .mx-page-stats__value {
        font-size: 34px;
    }

    .ft-page-builder-preview .mx-page-nav {
        gap: 18px;
        padding: 18px 22px;
        border: 1px solid rgba(148, 163, 184, 0.18);
        border-radius: 24px;
        background: rgba(255, 255, 255, 0.92);
        box-shadow: 0 20px 44px rgba(15, 23, 42, 0.08);
        backdrop-filter: blur(18px);
    }

    .ft-page-builder-preview .mx-page-nav--horizontal {
        grid-template-columns: auto minmax(0, 1fr) auto;
        align-items: center;
    }

    .ft-page-builder-preview .mx-page-nav__title {
        font-size: 16px;
        color: #0f172a;
        letter-spacing: .04em;
    }

    .ft-page-builder-preview .mx-page-nav__list {
        justify-content: center;
        gap: 12px;
    }

    .ft-page-builder-preview .mx-page-nav__link {
        min-height: 44px;
        padding: 0 18px;
        background: rgba(248, 250, 252, 0.88);
        border: 1px solid rgba(226, 232, 240, 0.92);
        transition: background .22s ease, color .22s ease, border-color .22s ease, box-shadow .22s ease, transform .22s ease;
    }

    .ft-page-builder-preview .mx-page-nav__link:hover {
        background: #ffffff;
        border-color: rgba(59, 130, 246, 0.24);
        box-shadow: 0 14px 28px rgba(15, 23, 42, 0.08);
        transform: translateY(-1px);
    }

    .ft-page-builder-preview .mx-page-nav__link.is-active {
        box-shadow: 0 16px 32px rgba(37, 99, 235, 0.2);
    }

    .ft-page-builder-preview .mx-page-nav__cta {
        min-height: 44px;
        padding: 0 18px;
        box-shadow: 0 16px 30px rgba(15, 23, 42, 0.18);
        transition: transform .22s ease, box-shadow .22s ease, background .22s ease;
    }

    .ft-page-builder-preview .mx-page-nav__cta:hover {
        background: #111c34;
        box-shadow: 0 20px 34px rgba(15, 23, 42, 0.22);
        transform: translateY(-1px);
    }

    .ft-page-builder-preview .mx-page-gallery__card,
    .ft-page-builder-preview .mx-page-faq__item,
    .ft-page-builder-preview .mx-page-stats__item,
    .ft-page-builder-preview .mx-page-model-card,
    .ft-page-builder-preview .mx-page-detail-card,
    .ft-page-builder-preview .mx-page-model-detail {
        transition: transform .24s ease, box-shadow .24s ease, border-color .24s ease;
    }

    .ft-page-builder-preview .mx-page-gallery__card:hover,
    .ft-page-builder-preview .mx-page-faq__item:hover,
    .ft-page-builder-preview .mx-page-stats__item:hover,
    .ft-page-builder-preview .mx-page-model-card:hover,
    .ft-page-builder-preview .mx-page-detail-card:hover,
    .ft-page-builder-preview .mx-page-model-detail:hover {
        transform: translateY(-4px);
        box-shadow: 0 24px 54px rgba(15, 23, 42, 0.12);
        border-color: rgba(59, 130, 246, 0.18);
    }

    .ft-page-builder-preview .mx-page-gallery__caption {
        font-size: 18px;
        line-height: 1.45;
    }

    .ft-page-builder-preview .mx-page-faq__item,
    .ft-page-builder-preview .mx-page-stats__item {
        position: relative;
        overflow: hidden;
    }

    .ft-page-builder-preview .mx-page-faq__item::before,
    .ft-page-builder-preview .mx-page-stats__item::before {
        content: "";
        position: absolute;
        inset: 0 auto auto 0;
        width: 100%;
        height: 1px;
        background: linear-gradient(90deg, rgba(37, 99, 235, 0.4) 0%, rgba(56, 189, 248, 0) 100%);
    }

    .ft-page-builder-preview .mx-page-cta {
        position: relative;
        overflow: hidden;
        box-shadow: 0 28px 60px rgba(15, 23, 42, 0.2);
    }

    .ft-page-builder-preview .mx-page-cta::before,
    .ft-page-builder-preview .mx-page-cta::after {
        content: "";
        position: absolute;
        border-radius: 999px;
        pointer-events: none;
    }

    .ft-page-builder-preview .mx-page-cta::before {
        top: -80px;
        right: -40px;
        width: 220px;
        height: 220px;
        background: radial-gradient(circle, rgba(96, 165, 250, 0.28) 0%, rgba(96, 165, 250, 0) 70%);
    }

    .ft-page-builder-preview .mx-page-cta::after {
        bottom: -120px;
        left: -40px;
        width: 260px;
        height: 260px;
        background: radial-gradient(circle, rgba(59, 130, 246, 0.18) 0%, rgba(59, 130, 246, 0) 72%);
    }

    .ft-page-builder-preview .mx-page-cta__body {
        position: relative;
        z-index: 1;
    }

    .ft-page-builder-preview .mx-page-model-list {
        display: grid;
        gap: 22px;
        padding: 0;
        border: 0;
        background: transparent;
    }

    .ft-page-builder-preview .mx-page-model-list__head,
    .ft-page-builder-preview .mx-page-model-detail__head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
    }

    .ft-page-builder-preview .mx-page-model-list__title,
    .ft-page-builder-preview .mx-page-model-detail__title {
        margin: 0;
        font-size: 34px;
        line-height: 1.18;
        color: #0f172a;
    }

    .ft-page-builder-preview .mx-page-model-list__grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 20px;
    }

    .ft-page-builder-preview .mx-page-model-list__grid--list {
        grid-template-columns: 1fr;
    }

    .ft-page-builder-preview .mx-page-model-card {
        display: grid;
        overflow: hidden;
        border-radius: 24px;
        border: 1px solid rgba(226, 232, 240, 0.92);
        background: rgba(255, 255, 255, 0.96);
        box-shadow: 0 18px 40px rgba(15, 23, 42, 0.08);
    }

    .ft-page-builder-preview .mx-page-model-card--list {
        grid-template-columns: minmax(240px, 320px) minmax(0, 1fr);
        align-items: stretch;
    }

    .ft-page-builder-preview .mx-page-model-card__media {
        position: relative;
        overflow: hidden;
        background: #e2e8f0;
        aspect-ratio: 4 / 3;
    }

    .ft-page-builder-preview .mx-page-model-card--list .mx-page-model-card__media {
        aspect-ratio: auto;
        min-height: 100%;
    }

    .ft-page-builder-preview .mx-page-model-card__media img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        transition: transform .28s ease;
    }

    .ft-page-builder-preview .mx-page-model-card:hover .mx-page-model-card__media img {
        transform: scale(1.04);
    }

    .ft-page-builder-preview .mx-page-model-card__content {
        display: grid;
        gap: 14px;
        padding: 24px;
    }

    .ft-page-builder-preview .mx-page-model-card__meta,
    .ft-page-builder-preview .mx-page-detail-card__meta {
        display: inline-flex;
        align-items: center;
        width: fit-content;
        min-height: 30px;
        padding: 0 12px;
        border-radius: 999px;
        background: #eff6ff;
        color: #2563eb;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: .04em;
    }

    .ft-page-builder-preview .mx-page-model-card__title {
        margin: 0;
        font-size: 22px;
        line-height: 1.35;
        color: #0f172a;
    }

    .ft-page-builder-preview .mx-page-model-card__title-link {
        color: inherit;
        text-decoration: none;
    }

    .ft-page-builder-preview .mx-page-model-card__title-link:hover {
        color: #2563eb;
    }

    .ft-page-builder-preview .mx-page-model-card__summary {
        margin: 0;
        color: #64748b;
        line-height: 1.85;
    }

    .ft-page-builder-preview .mx-page-model-card__footer {
        margin-top: auto;
        padding-top: 4px;
    }

    .ft-page-builder-preview .mx-page-model-card__link,
    .ft-page-builder-preview .mx-page-detail-card__button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 42px;
        padding: 0 16px;
        border-radius: 999px;
        background: #0f172a;
        color: #ffffff;
        text-decoration: none;
        font-weight: 700;
        transition: transform .22s ease, box-shadow .22s ease, background .22s ease;
    }

    .ft-page-builder-preview .mx-page-model-card__link:hover,
    .ft-page-builder-preview .mx-page-detail-card__button:hover {
        background: #111c34;
        box-shadow: 0 18px 32px rgba(15, 23, 42, 0.18);
        transform: translateY(-1px);
    }

    .ft-page-builder-preview .mx-page-model-card__link.is-static {
        background: #e2e8f0;
        color: #475569;
    }

    .ft-page-builder-preview .mx-page-model-detail {
        gap: 22px;
        padding: 0;
        border: 0;
        background: transparent;
        box-shadow: none;
    }

    .ft-page-builder-preview .mx-page-detail-card {
        padding: 28px;
        border-radius: 28px;
        border: 1px solid rgba(226, 232, 240, 0.92);
        background: rgba(255, 255, 255, 0.96);
        box-shadow: 0 22px 48px rgba(15, 23, 42, 0.08);
    }

    .ft-page-builder-preview .mx-page-detail-card__content {
        display: grid;
        align-content: start;
        gap: 16px;
    }

    .ft-page-builder-preview .mx-page-detail-card__title,
    .ft-page-builder-preview .mx-page-detail-card__summary,
    .ft-page-builder-preview .mx-page-detail-card__body {
        margin: 0;
    }

    .ft-page-builder-preview .mx-page-detail-card__actions {
        margin-top: 8px;
    }

    .ft-page-builder-preview.is-device-tablet .mx-page-nav--horizontal {
        grid-template-columns: 1fr;
    }

    .ft-page-builder-preview.is-device-tablet .mx-page-nav__list {
        justify-content: flex-start;
    }

    .ft-page-builder-preview.is-device-tablet .mx-page-model-list__grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .ft-page-builder-preview.is-device-tablet .mx-page-model-card--list,
    .ft-page-builder-preview.is-device-tablet .mx-page-detail-card--media {
        grid-template-columns: 1fr;
    }

    .ft-page-builder-preview.is-device-mobile .mx-page-model-list__title,
    .ft-page-builder-preview.is-device-mobile .mx-page-model-detail__title {
        font-size: 28px;
    }

    .ft-page-builder-preview.is-device-mobile .mx-page-model-list__grid,
    .ft-page-builder-preview.is-device-mobile .mx-page-model-card--list {
        grid-template-columns: 1fr;
    }

    .ft-page-builder-preview.is-device-mobile .mx-page-nav {
        padding: 18px;
    }

    .ft-page-builder-preview.is-device-mobile .mx-page-detail-card {
        padding: 22px;
    }

    @media (max-width: 991px) {
        .ft-page-form__grid {
            grid-template-columns: 1fr;
        }

        .ft-page-form__workspace-meta {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .ft-page-form__panel-header,
        .ft-page-form__toolbar {
            flex-direction: column;
            align-items: flex-start;
        }

        .ft-page-builder__theme-head,
        .ft-page-builder__theme-actions {
            flex-direction: column;
            align-items: flex-start;
        }

        .ft-page-builder__theme-summary {
            margin-left: 0;
            margin-right: 0;
        }

        .ft-page-form__actions {
            justify-content: flex-start;
            flex-wrap: wrap;
        }

        .ft-page-form__catalog {
            grid-template-columns: 1fr;
        }

        .ft-page-builder {
            grid-template-columns: 1fr;
        }

        .ft-page-builder__field-grid {
            grid-template-columns: 1fr;
        }

        .ft-page-builder__theme-fields {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .ft-page-builder-preview .mx-page-row,
        .ft-page-builder-preview .mx-page-placeholder-grid {
            grid-template-columns: 1fr;
        }

        .ft-page-builder-preview .mx-page-col {
            grid-column: span 1 !important;
        }

        .ft-page-form__floating-bar {
            position: sticky;
            bottom: 12px;
            width: 100%;
            max-width: none;
            margin-left: 0;
            margin-right: 0;
            flex-direction: column;
            align-items: flex-start;
        }

        .ft-page-form__floating-actions {
            width: 100%;
            justify-content: flex-start;
        }
    }

    @media (max-width: 640px) {
        .ft-page-form__workspace-meta {
            grid-template-columns: 1fr;
        }

        .ft-page-builder__theme-fields {
            grid-template-columns: 1fr;
        }

        .ft-page-builder__theme-summary {
            width: 100%;
        }
    }
</style>
<body>
@include(moduleAdminTemplate($moduleName)."public.nav")
<div class="page-container">
    <div class="page-content">
        @include(moduleAdminTemplate($moduleName)."public.left")
        <div class="content-wrapper">
            <div class="content" style="margin-top: 1rem;">
                @include(moduleAdminTemplate($moduleName)."public.crumb",['breadcrumb'=>[$pageData['title'],$pageData['subtitle']]])

                <form method="post" class="ft-page-form" id="pageFormEditor">
                    @csrf
                    @if($formData['id'])
                        <input type="hidden" name="id" value="{{$formData['id']}}">
                    @endif

                    <div class="alert alert-info alert-styled-left ft-page-form__note">
                        <span>这一版先把页面骨架建好：多页面、路由、绑定模型、布局 JSON、HTML、CSS、JS 都能管起来。后面要接 DIV 可视化拖拽时，直接把结构写回 `layout_schema` 就行。</span>
                    </div>

                    <div class="ft-page-form__workspace-meta">
                        <div class="ft-page-form__workspace-card">
                            <div class="ft-page-form__workspace-label">当前页面</div>
                            <div class="ft-page-form__workspace-value" id="pageFormWorkspaceName">{{$formData['name'] ?: '未命名页面'}}</div>
                        </div>
                        <div class="ft-page-form__workspace-card">
                            <div class="ft-page-form__workspace-label">访问路径</div>
                            <div class="ft-page-form__workspace-value {{ $formData['slug'] ? '' : 'is-muted' }}" id="pageFormWorkspaceSlug">{{$formData['slug'] ? ('/p/' . ltrim($formData['slug'], '/')) : '保存后生成正式地址'}}</div>
                        </div>
                        <div class="ft-page-form__workspace-card">
                            <div class="ft-page-form__workspace-label">编辑模式</div>
                            <div class="ft-page-form__workspace-value" id="pageFormWorkspaceMode">{{$formData['builder_type'] === 'html' ? 'HTML 代码布局' : 'Visual 布局编辑器'}}</div>
                        </div>
                        <div class="ft-page-form__workspace-card">
                            <div class="ft-page-form__workspace-label">保存状态</div>
                            <div class="ft-page-form__workspace-value" id="pageFormWorkspaceStatus">已同步</div>
                        </div>
                    </div>

                    <div class="ft-page-form__panel">
                        <div class="ft-page-form__panel-header">
                            <div>
                                <h3 class="ft-page-form__panel-title">基础信息</h3>
                                <p class="ft-page-form__panel-desc">先定义这张页面是什么、怎么访问、是否启用、是否显示到导航。</p>
                            </div>
                            <a href="{{url('admin/formtools/pageList')}}" class="btn btn-default btn-sm">返回页面列表</a>
                        </div>
                        <div class="ft-page-form__body">
                            <div class="ft-page-form__grid">
                                <div>
                                    <label class="ft-page-form__label">页面名称</label>
                                    <input type="text" name="name" value="{{$formData['name']}}" class="ft-page-form__control" placeholder="例如：关于我们">
                                </div>
                                <div>
                                    <label class="ft-page-form__label">页面标识</label>
                                    <input type="text" name="identification" value="{{$formData['identification']}}" class="ft-page-form__control" placeholder="例如：about_us">
                                    <div class="ft-page-form__help">仅允许小写字母、数字、下划线和中划线，用于系统内部识别。</div>
                                </div>
                                <div>
                                    <label class="ft-page-form__label">访问路径</label>
                                    <input type="text" name="slug" value="{{$formData['slug']}}" class="ft-page-form__control" placeholder="例如：about 或 company/about">
                                    <div class="ft-page-form__help">正式对外地址将使用 `/p/你的路径`。后台预览会走独立预览地址，不再暴露模块路径。</div>
                                </div>
                                <div>
                                    <label class="ft-page-form__label">页面模板</label>
                                    <input type="text" name="template" value="{{$formData['template']}}" class="ft-page-form__control" placeholder="default">
                                </div>
                                <div>
                                    <label class="ft-page-form__label">页面类型</label>
                                    <select name="type" class="ft-page-form__control">
                                        <option value="single" {{$formData['type'] === 'single' ? 'selected' : ''}}>single 单页</option>
                                        <option value="list" {{$formData['type'] === 'list' ? 'selected' : ''}}>list 列表页</option>
                                        <option value="custom" {{$formData['type'] === 'custom' ? 'selected' : ''}}>custom 自定义页</option>
                                        <option value="landing" {{$formData['type'] === 'landing' ? 'selected' : ''}}>landing 专题页</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="ft-page-form__label">页面分类</label>
                                    <select name="category_id" class="ft-page-form__control">
                                        <option value="">不分类</option>
                                        @foreach($categories as $category)
                                            <option value="{{$category->id}}" {{(string) $formData['category_id'] === (string) $category->id ? 'selected' : ''}}>{{$category->name}}</option>
                                        @endforeach
                                    </select>
                                    <div class="ft-page-form__help">如果要做频道、专题、导航分组，建议先建好分类再挂页面。</div>
                                </div>
                                <div>
                                    <label class="ft-page-form__label">绑定模型</label>
                                    <select name="model_id" class="ft-page-form__control">
                                        <option value="">不绑定模型</option>
                                        @foreach($models as $model)
                                            <option value="{{$model->id}}" {{(string) $formData['model_id'] === (string) $model->id ? 'selected' : ''}}>{{$model->name}} ({{$model->identification}})</option>
                                        @endforeach
                                    </select>
                                    <div class="ft-page-form__help">绑定后，这张页面就能和现有模型形成“数据层 + 页面层”的组合关系。</div>
                                </div>
                                <div>
                                    <label class="ft-page-form__label">状态</label>
                                    <div class="ft-page-form__radio-group">
                                        <label class="ft-page-form__radio"><input type="radio" name="status" value="1" {{$formData['status'] === '1' ? 'checked' : ''}}> 启用</label>
                                        <label class="ft-page-form__radio"><input type="radio" name="status" value="0" {{$formData['status'] === '0' ? 'checked' : ''}}> 停用</label>
                                    </div>
                                </div>
                                <div>
                                    <label class="ft-page-form__label">导航显示</label>
                                    <div class="ft-page-form__radio-group">
                                        <label class="ft-page-form__radio"><input type="radio" name="is_nav" value="1" {{$formData['is_nav'] === '1' ? 'checked' : ''}}> 显示</label>
                                        <label class="ft-page-form__radio"><input type="radio" name="is_nav" value="0" {{$formData['is_nav'] === '0' ? 'checked' : ''}}> 隐藏</label>
                                    </div>
                                </div>
                                <div>
                                    <label class="ft-page-form__label">首页显示</label>
                                    <div class="ft-page-form__radio-group">
                                        <label class="ft-page-form__radio"><input type="radio" name="is_home" value="1" {{$formData['is_home'] === '1' ? 'checked' : ''}}> 设为首页</label>
                                        <label class="ft-page-form__radio"><input type="radio" name="is_home" value="0" {{$formData['is_home'] === '0' ? 'checked' : ''}}> 不作为首页</label>
                                    </div>
                                    <div class="ft-page-form__help">站点根路径 `/` 会按“页面优先、模块第二、默认第三”决定首页内容。设为首页后，会自动取消其它页面的首页标记。</div>
                                </div>
                                <div>
                                    <label class="ft-page-form__label">排序</label>
                                    <input type="number" name="sort" value="{{$formData['sort']}}" class="ft-page-form__control" placeholder="0">
                                </div>
                                <div>
                                    <label class="ft-page-form__label">编辑模式</label>
                                    <div class="ft-page-form__radio-group">
                                        <label class="ft-page-form__radio"><input type="radio" name="builder_type" value="visual" {{$formData['builder_type'] === 'visual' ? 'checked' : ''}}> visual 布局 JSON</label>
                                        <label class="ft-page-form__radio"><input type="radio" name="builder_type" value="html" {{$formData['builder_type'] === 'html' ? 'checked' : ''}}> html 代码布局</label>
                                    </div>
                                </div>
                                <div class="ft-page-form__full">
                                    <label class="ft-page-form__label">备注</label>
                                    <input type="text" name="remark" value="{{$formData['remark']}}" class="ft-page-form__control" placeholder="页面用途、运营备注、模板说明">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="ft-page-form__panel ft-page-form__panel--builder">
                        <div class="ft-page-form__panel-header">
                            <div>
                                <h3 class="ft-page-form__panel-title">布局与结构</h3>
                                <p class="ft-page-form__panel-desc">先存页面布局结构，后续拖拽编辑器就直接读写这里。当前支持 JSON 草稿和 HTML 代码并存。</p>
                            </div>
                            @if($previewUrl || $publicUrl)
                                <div class="ft-page-form__preview">
                                    @if($previewUrl)
                                        预览地址
                                        <code>{{$previewUrl}}</code>
                                    @endif
                                    @if($publicUrl)
                                        <br>正式地址
                                        <code>{{$publicUrl}}</code>
                                    @endif
                                </div>
                            @endif
                        </div>
                        <div class="ft-page-form__body">
                            <div class="ft-page-form__toolbar">
                                <div class="ft-page-form__help">`layout_schema` 适合作为 DIV 结构和拖拽编排的最终存储；`page_html` 适合当前先用代码模式快速布局。</div>
                                <div class="ft-page-form__toolbar-right">
                                    <button type="button" class="btn btn-default btn-sm" id="fillDefaultSchema">填入示例布局 JSON</button>
                                    @if($previewUrl)
                                        <a href="{{$previewUrl}}" target="_blank" class="btn btn-info btn-sm">打开预览</a>
                                    @endif
                                    @if($publicUrl)
                                        <a href="{{$publicUrl}}" target="_blank" class="btn btn-default btn-sm">打开正式页</a>
                                    @endif
                                </div>
                            </div>

                            <div class="ft-page-builder" id="pageBuilderWorkspace">
                                <div class="ft-page-builder__panel ft-page-builder__panel--overlay ft-page-builder__panel--catalog">
                                    <div class="ft-page-builder__panel-header">
                                        <div class="ft-page-builder__panel-head">
                                            <div>
                                                <span class="ft-page-builder__panel-kicker">Library</span>
                                                <h4 class="ft-page-builder__panel-title">组件库</h4>
                                                <p class="ft-page-builder__panel-desc">搜索、筛选、直接插入组件。</p>
                                            </div>
                                            <div class="ft-page-builder__panel-actions">
                                                <div class="ft-page-builder__panel-tip" data-panel-tip="catalog">
                                                    <button type="button" class="ft-page-builder__panel-tip-toggle" data-panel-tip-toggle="catalog" title="查看组件库说明">?</button>
                                                    <div class="ft-page-builder__panel-tip-body">
                                                        <div class="ft-page-builder__panel-tip-section">
                                                            <strong class="ft-page-builder__panel-tip-title">使用说明</strong>
                                                            <p class="ft-page-builder__panel-tip-text">常用商业区块直接点就能插；如果当前选中的是容器，会优先放进这个容器里。</p>
                                                        </div>
                                                        <div class="ft-page-builder__panel-tip-section">
                                                            <strong class="ft-page-builder__panel-tip-title">组件库状态</strong>
                                                            <div class="ft-page-builder__catalog-meta" id="pageBuilderCatalogMeta">组件库会按类型自动分组，方便快速搭建常见商业页面。</div>
                                                        </div>
                                                        <div class="ft-page-builder__panel-tip-section">
                                                            <strong class="ft-page-builder__panel-tip-title">当前插入目标</strong>
                                                            <div class="ft-page-builder__catalog-target" id="pageBuilderCatalogTarget">当前未选中区块，新组件会优先插入到首个可用容器中。</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="ft-page-builder__panel-body ft-page-builder__panel-body--catalog">
                                        <div class="ft-page-builder__catalog-shell">
                                            <div class="ft-page-builder__catalog-toolbox" id="pageBuilderCatalogFilters"></div>
                                            <div class="ft-page-builder__catalog-main">
                                                <div class="ft-page-builder__catalog-toolbar">
                                                    <div class="ft-page-builder__catalog-toolbar-head">
                                                        <div class="ft-page-builder__catalog-current">
                                                            <h5 class="ft-page-builder__catalog-current-title" id="pageBuilderCatalogCurrentTitle">布局组件</h5>
                                                            <div class="ft-page-builder__catalog-current-meta" id="pageBuilderCatalogCurrentMeta">像 PS 工具栏一样，先切一个工具分组，再在右侧添加当前分组组件。</div>
                                                        </div>
                                                    </div>
                                                    <input type="text" class="ft-page-form__control" id="pageBuilderCatalogSearch" placeholder="搜索当前工具组，例如：标题、按钮、卡片、模型">
                                                </div>
                                                <div class="ft-page-builder__catalog-quick" id="pageBuilderCatalogQuick"></div>
                                                <div class="ft-page-builder__catalog-list" id="pageBuilderCatalog">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="ft-page-builder__panel ft-page-builder__panel--canvas">
                                    <div class="ft-page-builder__panel-header">
                                        <div class="ft-page-builder__panel-head">
                                            <div>
                                                <span class="ft-page-builder__panel-kicker">Workspace</span>
                                                <h4 class="ft-page-builder__panel-title">布局画布</h4>
                                                <p class="ft-page-builder__panel-desc">选中、插入、拖拽、预览都在这里完成。</p>
                                            </div>
                                            <div class="ft-page-builder__panel-actions">
                                                <div class="ft-page-builder__panel-tip" data-panel-tip="canvas">
                                                    <button type="button" class="ft-page-builder__panel-tip-toggle" data-panel-tip-toggle="canvas" title="查看画布说明">?</button>
                                                    <div class="ft-page-builder__panel-tip-body">
                                                        <div class="ft-page-builder__panel-tip-section">
                                                            <strong class="ft-page-builder__panel-tip-title">操作说明</strong>
                                                            <p class="ft-page-builder__panel-tip-text">点区块间的 `+` 可插入同级内容；点容器里的快捷按钮可直接加行、加列或整套布局；拖拽仍然支持排序和放入容器。</p>
                                                        </div>
                                                        <div class="ft-page-builder__panel-tip-section">
                                                            <strong class="ft-page-builder__panel-tip-title">当前画布状态</strong>
                                                            <div class="ft-page-builder__canvas-status" id="pageBuilderCanvasStatus"></div>
                                                        </div>
                                                        <div class="ft-page-builder__panel-tip-section">
                                                            <strong class="ft-page-builder__panel-tip-title">实时预览说明</strong>
                                                            <div class="ft-page-builder__live-preview-note" id="pageBuilderLivePreviewNote">`model_list`、`model_detail` 在编辑器里先展示结构占位，真正的动态数据以后台预览页和前台页面为准。</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="ft-page-builder__panel-body ft-page-builder__panel-body--canvas">
                                        <div class="ft-page-builder__notice" id="pageBuilderNotice"></div>
                                        <div class="ft-page-builder__canvas-toolbar">
                                            <div class="ft-page-builder__canvas-actions">
                                                <button type="button" class="btn btn-default btn-sm" id="addRootSection">新增空白区块</button>
                                                <button type="button" class="btn btn-default btn-sm" id="reloadSchemaCanvas">从 JSON 载入</button>
                                            </div>
                                        </div>
                                        <details class="ft-page-builder__fold ft-page-builder__theme-fold" id="pageBuilderThemeFold">
                                            <summary class="ft-page-builder__fold-summary">
                                                <span class="ft-page-builder__fold-title">页面主题</span>
                                                <span class="ft-page-builder__theme-summary" id="pageBuilderThemeSummary"></span>
                                                <span class="ft-page-builder__fold-meta">默认收起</span>
                                            </summary>
                                            <div class="ft-page-builder__fold-body">
                                                <div class="ft-page-builder__theme-panel">
                                                    <div class="ft-page-builder__theme-head">
                                                        <div>
                                                            <h5 class="ft-page-builder__theme-title">页面主题</h5>
                                                            <p class="ft-page-builder__theme-desc">这里控制整页的品牌色、表面色、卡片圆角和主视觉渐变，配置会直接写进 `layout_schema.theme`，前台渲染和实时预览都会同步。</p>
                                                        </div>
                                                        <div class="ft-page-builder__theme-presets" id="pageBuilderThemePresets">
                                                            <button type="button" class="ft-page-builder__theme-chip" data-theme-preset="brand-blue">品牌蓝</button>
                                                            <button type="button" class="ft-page-builder__theme-chip" data-theme-preset="business-luxe">高端商务</button>
                                                            <button type="button" class="ft-page-builder__theme-chip" data-theme-preset="tech-future">科技感</button>
                                                            <button type="button" class="ft-page-builder__theme-chip" data-theme-preset="dark-mode">深色版</button>
                                                            <button type="button" class="ft-page-builder__theme-chip" data-theme-preset="aurora-purple">极光紫</button>
                                                            <button type="button" class="ft-page-builder__theme-chip" data-theme-preset="emerald-dark">深翠绿</button>
                                                        </div>
                                                    </div>
                                                    <div class="ft-page-builder__theme-fields">
                                                        <div class="ft-page-builder__theme-field">
                                                            <label for="builderThemePrimary">主品牌色</label>
                                                            <div class="ft-page-builder__theme-input"><span class="ft-page-builder__theme-swatch" data-theme-preview="primary" data-theme-preview-kind="color"></span><input type="color" class="ft-page-builder__theme-picker" data-theme-color-picker="primary" value="#2563eb"><input type="text" class="ft-page-form__control" id="builderThemePrimary" data-theme-field="primary" placeholder="#2563eb"></div>
                                                        </div>
                                                        <div class="ft-page-builder__theme-field">
                                                            <label for="builderThemeAccent">深色强调</label>
                                                            <div class="ft-page-builder__theme-input"><span class="ft-page-builder__theme-swatch" data-theme-preview="accent" data-theme-preview-kind="color"></span><input type="color" class="ft-page-builder__theme-picker" data-theme-color-picker="accent" value="#0f172a"><input type="text" class="ft-page-form__control" id="builderThemeAccent" data-theme-field="accent" placeholder="#0f172a"></div>
                                                        </div>
                                                        <div class="ft-page-builder__theme-field">
                                                            <label for="builderThemeSurface">卡片底色</label>
                                                            <div class="ft-page-builder__theme-input"><span class="ft-page-builder__theme-swatch" data-theme-preview="surfaceElevated" data-theme-preview-kind="color"></span><input type="text" class="ft-page-form__control" id="builderThemeSurface" data-theme-field="surfaceElevated" placeholder="rgba(255,255,255,.96)"></div>
                                                        </div>
                                                        <div class="ft-page-builder__theme-field">
                                                            <label for="builderThemeText">正文颜色</label>
                                                            <div class="ft-page-builder__theme-input"><span class="ft-page-builder__theme-swatch" data-theme-preview="text" data-theme-preview-kind="color"></span><input type="color" class="ft-page-builder__theme-picker" data-theme-color-picker="text" value="#0f172a"><input type="text" class="ft-page-form__control" id="builderThemeText" data-theme-field="text" placeholder="#0f172a"></div>
                                                        </div>
                                                        <div class="ft-page-builder__theme-field">
                                                            <label for="builderThemeMuted">辅助文字</label>
                                                            <div class="ft-page-builder__theme-input"><span class="ft-page-builder__theme-swatch" data-theme-preview="textMuted" data-theme-preview-kind="color"></span><input type="color" class="ft-page-builder__theme-picker" data-theme-color-picker="textMuted" value="#64748b"><input type="text" class="ft-page-form__control" id="builderThemeMuted" data-theme-field="textMuted" placeholder="#64748b"></div>
                                                        </div>
                                                        <div class="ft-page-builder__theme-field">
                                                            <label for="builderThemeBorder">边框颜色</label>
                                                            <div class="ft-page-builder__theme-input"><span class="ft-page-builder__theme-swatch" data-theme-preview="border" data-theme-preview-kind="color"></span><input type="color" class="ft-page-builder__theme-picker" data-theme-color-picker="border" value="#e2e8f0"><input type="text" class="ft-page-form__control" id="builderThemeBorder" data-theme-field="border" placeholder="#e2e8f0"></div>
                                                        </div>
                                                        <div class="ft-page-builder__theme-field">
                                                            <label for="builderThemeHero">主视觉渐变</label>
                                                            <div class="ft-page-builder__theme-input"><span class="ft-page-builder__theme-swatch" data-theme-preview="heroGradient" data-theme-preview-kind="gradient"></span><input type="text" class="ft-page-form__control" id="builderThemeHero" data-theme-field="heroGradient" placeholder="linear-gradient(...)"></div>
                                                        </div>
                                                        <div class="ft-page-builder__theme-field">
                                                            <label for="builderThemeRadius">卡片圆角</label>
                                                            <div class="ft-page-builder__theme-input"><span class="ft-page-builder__theme-swatch" data-theme-preview="radiusCard" data-theme-preview-kind="radius"></span><input type="text" class="ft-page-form__control" id="builderThemeRadius" data-theme-field="radiusCard" placeholder="24px"></div>
                                                        </div>
                                                        <div class="ft-page-builder__theme-field">
                                                            <label for="builderThemeHeading">标题颜色</label>
                                                            <div class="ft-page-builder__theme-input"><span class="ft-page-builder__theme-swatch" data-theme-preview="heading" data-theme-preview-kind="color"></span><input type="color" class="ft-page-builder__theme-picker" data-theme-color-picker="heading" value="#0f172a"><input type="text" class="ft-page-form__control" id="builderThemeHeading" data-theme-field="heading" placeholder="#0f172a"></div>
                                                        </div>
                                                        <div class="ft-page-builder__theme-field">
                                                            <label for="builderThemeSurfaceMuted">页面底色</label>
                                                            <div class="ft-page-builder__theme-input"><span class="ft-page-builder__theme-swatch" data-theme-preview="surfaceMuted" data-theme-preview-kind="color"></span><input type="color" class="ft-page-builder__theme-picker" data-theme-color-picker="surfaceMuted" value="#f8fafc"><input type="text" class="ft-page-form__control" id="builderThemeSurfaceMuted" data-theme-field="surfaceMuted" placeholder="#f8fafc"></div>
                                                        </div>
                                                        <div class="ft-page-builder__theme-field">
                                                            <label for="builderThemeShadowSoft">卡片阴影</label>
                                                            <div class="ft-page-builder__theme-input"><span class="ft-page-builder__theme-swatch" data-theme-preview="shadowSoft" data-theme-preview-kind="shadow"></span><input type="text" class="ft-page-form__control" id="builderThemeShadowSoft" data-theme-field="shadowSoft" placeholder="0 18px 40px rgba(...)"></div>
                                                        </div>
                                                        <div class="ft-page-builder__theme-field">
                                                            <label for="builderThemeShadowStrong">强调阴影</label>
                                                            <div class="ft-page-builder__theme-input"><span class="ft-page-builder__theme-swatch" data-theme-preview="shadowStrong" data-theme-preview-kind="shadow"></span><input type="text" class="ft-page-form__control" id="builderThemeShadowStrong" data-theme-field="shadowStrong" placeholder="0 28px 60px rgba(...)"></div>
                                                        </div>
                                                        <div class="ft-page-builder__theme-field">
                                                            <label for="builderThemeSectionRadius">区块圆角</label>
                                                            <div class="ft-page-builder__theme-input"><span class="ft-page-builder__theme-swatch" data-theme-preview="radiusSection" data-theme-preview-kind="radius"></span><input type="text" class="ft-page-form__control" id="builderThemeSectionRadius" data-theme-field="radiusSection" placeholder="28px"></div>
                                                        </div>
                                                        <div class="ft-page-builder__theme-field">
                                                            <label for="builderThemeButtonStyle">按钮风格</label>
                                                            <select class="ft-page-form__control ft-page-builder__theme-select" id="builderThemeButtonStyle" data-theme-field="buttonStyle">
                                                                <option value="solid">商务立体</option>
                                                                <option value="soft">柔和胶囊</option>
                                                                <option value="glow">科技光感</option>
                                                            </select>
                                                        </div>
                                                        <div class="ft-page-builder__theme-field">
                                                            <label for="builderThemeCardStyle">卡片风格</label>
                                                            <select class="ft-page-form__control ft-page-builder__theme-select" id="builderThemeCardStyle" data-theme-field="cardStyle">
                                                                <option value="elevated">浮层卡片</option>
                                                                <option value="outline">描边极简</option>
                                                                <option value="glass">玻璃卡片</option>
                                                            </select>
                                                        </div>
                                                        <div class="ft-page-builder__theme-field">
                                                            <label for="builderThemeNavStyle">导航风格</label>
                                                            <select class="ft-page-form__control ft-page-builder__theme-select" id="builderThemeNavStyle" data-theme-field="navStyle">
                                                                <option value="glass">玻璃悬浮</option>
                                                                <option value="solid">深色品牌条</option>
                                                                <option value="minimal">极简透明</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="ft-page-builder__theme-actions">
                                                        <div class="ft-page-builder__theme-meta" id="pageBuilderThemeMeta">建议先选一个主题基线，再微调主色、渐变和圆角，这样整站会更完整。</div>
                                                        <button type="button" class="btn btn-default btn-sm" id="pageBuilderThemeReset">恢复默认主题</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </details>
                                        <details class="ft-page-builder__fold">
                                            <summary class="ft-page-builder__fold-summary">
                                                <span class="ft-page-builder__fold-title">快捷模板</span>
                                                <span class="ft-page-builder__fold-meta">按需展开</span>
                                            </summary>
                                            <div class="ft-page-builder__fold-body">
                                                <div class="ft-page-builder__field-help is-static">导入区块模板会清空当前页面，再替换成当前选中的整段结构。</div>
                                                <div class="ft-page-builder__preset-list" id="pageBuilderPresetList">
                                                <button type="button" class="btn btn-default btn-xs" data-builder-preset="hero">Hero 首屏</button>
                                                <button type="button" class="btn btn-default btn-xs" data-builder-preset="two-column">双列 6-6</button>
                                                <button type="button" class="btn btn-default btn-xs" data-builder-preset="three-column">三列 4-4-4</button>
                                                <button type="button" class="btn btn-default btn-xs" data-builder-preset="image-text">左图右文</button>
                                                <button type="button" class="btn btn-default btn-xs" data-builder-preset="feature-cards">三卡功能区</button>
                                                <button type="button" class="btn btn-default btn-xs" data-builder-preset="faq">FAQ 问答区</button>
                                                <button type="button" class="btn btn-default btn-xs" data-builder-preset="cta-band">通栏 CTA</button>
                                                <button type="button" class="btn btn-default btn-xs" data-builder-preset="asymmetric-columns">左右分栏 4-8</button>
                                                <button type="button" class="btn btn-default btn-xs" data-builder-preset="four-column">四列矩阵</button>
                                                <button type="button" class="btn btn-default btn-xs" data-builder-preset="lead-form">表单咨询区</button>
                                                </div>
                                                <div class="ft-page-builder__field-help is-static" style="margin-top: 10px;">导入整页模板会清空当前页面，一次替换为适配 PC、平板和手机的完整页面骨架。</div>
                                                <div class="ft-page-builder__preset-list" id="pageBuilderTemplateList">
                                                <button type="button" class="btn btn-default btn-xs" data-builder-template="page-corp-home">企业官网首页</button>
                                                <button type="button" class="btn btn-default btn-xs" data-builder-template="page-service-landing">服务落地页</button>
                                                <button type="button" class="btn btn-default btn-xs" data-builder-template="page-product-detail">产品详情页</button>
                                                <button type="button" class="btn btn-default btn-xs" data-builder-template="page-article-list">资讯列表页</button>
                                                <button type="button" class="btn btn-default btn-xs" data-builder-template="page-contact-convert">联系咨询页</button>
                                                </div>
                                            </div>
                                        </details>
                                        <div class="ft-page-builder__canvas-stage">
                                            <div class="ft-page-builder__breadcrumb" id="pageBuilderSelectionBreadcrumb"></div>
                                            <div class="ft-page-builder__selection-bar" id="pageBuilderSelectionBar"></div>
                                            <div id="pageBuilderCanvas"></div>
                                            <details class="ft-page-builder__fold ft-page-builder__live-preview-wrap">
                                                <summary class="ft-page-builder__fold-summary">
                                                    <span class="ft-page-builder__fold-title">实时预览</span>
                                                    <span class="ft-page-builder__fold-meta">默认收起</span>
                                                </summary>
                                                <div class="ft-page-builder__live-preview-shell">
                                                    <div class="ft-page-builder__live-preview-toolbar">
                                                        <div class="ft-page-builder__device-switch" id="pageBuilderDeviceSwitch">
                                                            <button type="button" class="btn btn-default btn-xs" data-builder-device="desktop">PC</button>
                                                            <button type="button" class="btn btn-default btn-xs" data-builder-device="tablet">平板</button>
                                                            <button type="button" class="btn btn-default btn-xs" data-builder-device="mobile">手机</button>
                                                            <span class="ft-page-builder__device-meta" id="pageBuilderDeviceMeta"></span>
                                                        </div>
                                                    </div>
                                                    <div class="ft-page-builder__live-preview-body" id="pageBuilderLivePreview"></div>
                                                </div>
                                            </details>
                                        </div>
                                    </div>
                                </div>

                                <div class="ft-page-builder__inspector-track" id="pageBuilderInspectorTrack">
                                <div class="ft-page-builder__panel ft-page-builder__panel--overlay ft-page-builder__panel--inspector">
                                    <div class="ft-page-builder__panel-header">
                                        <div class="ft-page-builder__panel-head">
                                            <div>
                                                <span class="ft-page-builder__panel-kicker">Inspector</span>
                                                <h4 class="ft-page-builder__panel-title">属性面板</h4>
                                                <p class="ft-page-builder__panel-desc">优先改常用项，复杂配置再展开。</p>
                                            </div>
                                            <div class="ft-page-builder__panel-actions">
                                                <div class="ft-page-builder__panel-tip" data-panel-tip="inspector">
                                                    <button type="button" class="ft-page-builder__panel-tip-toggle" data-panel-tip-toggle="inspector" title="查看属性面板说明">?</button>
                                                    <div class="ft-page-builder__panel-tip-body">
                                                        <div class="ft-page-builder__panel-tip-section">
                                                            <strong class="ft-page-builder__panel-tip-title">使用说明</strong>
                                                            <p class="ft-page-builder__panel-tip-text">先改常用项，再改外观和布局；只有复杂场景再去碰 JSON。没有选中区块时，这里不会显示具体配置。</p>
                                                        </div>
                                                        <div class="ft-page-builder__panel-tip-section">
                                                            <strong class="ft-page-builder__panel-tip-title">推荐顺序</strong>
                                                            <p class="ft-page-builder__panel-tip-text">常用看内容字段，外观看颜色与间距，布局看响应式，低频场景再展开高级和 JSON。</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="ft-page-builder__panel-body ft-page-builder__panel-body--inspector">
                                        <div class="ft-page-builder__inspector-empty" id="pageBuilderInspectorEmpty">
                                            <span class="ft-page-builder__empty-kicker">Inspector</span>
                                            <strong>先在画布选一个区块</strong>
                                            <span>这里会自动切到它的常用设置。新增区块后也会自动选中。</span>
                                        </div>
                                        <div id="pageBuilderInspectorForm" style="display: none;">
                                            <div class="ft-page-builder__inspector-shell">
                                                <div class="ft-page-builder__inspector-main">
                                                    <div class="ft-page-builder__inspector-tabs" id="pageBuilderInspectorTabs">
                                                        <button type="button" class="ft-page-builder__inspector-tab is-active" data-inspector-tab="content" title="常用"><span class="ft-page-builder__inspector-tab-icon">常</span><span class="ft-page-builder__inspector-tab-label">常用</span></button>
                                                        <button type="button" class="ft-page-builder__inspector-tab" data-inspector-tab="style" title="样式"><span class="ft-page-builder__inspector-tab-icon">样</span><span class="ft-page-builder__inspector-tab-label">样式</span></button>
                                                        <button type="button" class="ft-page-builder__inspector-tab" data-inspector-tab="responsive" title="布局"><span class="ft-page-builder__inspector-tab-icon">布</span><span class="ft-page-builder__inspector-tab-label">布局</span></button>
                                                        <button type="button" class="ft-page-builder__inspector-tab" data-inspector-tab="advanced" title="JSON"><span class="ft-page-builder__inspector-tab-icon">码</span><span class="ft-page-builder__inspector-tab-label">JSON</span></button>
                                                    </div>
                                                    <div class="ft-page-builder__inspector-sticky-actions" id="pageBuilderInspectorStickyActions"></div>
                                                    <div class="ft-page-builder__inspector-outline" id="pageBuilderInspectorOutline"></div>
                                                    <div class="ft-page-builder__inspector-summary" id="pageBuilderInspectorSummary"></div>
                                                    <div class="ft-page-builder__inspector-panel is-active" data-inspector-panel="content">
                                                        <div class="ft-page-builder__subpanel" id="builderVisualConfig" style="display: none;">
                                                    <h5 class="ft-page-builder__subpanel-title">常用设置</h5>
                                                    <p class="ft-page-builder__subpanel-desc">高频项直接改，先不用碰 JSON。</p>
                                                    <div class="ft-page-builder__field-help is-static" id="builderVisualHint">选中常用区块后，这里会出现对应的可视化字段。</div>
                                                    <div class="ft-page-builder__field ft-page-form__full">
                                                        <label>锚点 ID</label>
                                                        <input type="text" id="builderAnchorId" placeholder="例如：hero、products、contact">
                                                        <div class="ft-page-builder__field-help is-static">导航和侧边栏填 `#hero`、`#contact` 这类链接时，会滚动到这里设置的锚点。</div>
                                                    </div>

                                                    <div id="builderSectionVisualWrap" style="display: none;">
                                                        <div class="ft-page-builder__field-grid">
                                                            <div class="ft-page-builder__field">
                                                                <label>内容宽度模式</label>
                                                                <select id="builderSectionContentWidth">
                                                                    <option value="full">通栏</option>
                                                                    <option value="contained">居中内容</option>
                                                                </select>
                                                            </div>
                                                            <div class="ft-page-builder__field">
                                                                <label>内容区宽度</label>
                                                                <input type="text" id="builderSectionInnerWidth" placeholder="1180px">
                                                            </div>
                                                            <div class="ft-page-builder__field ft-page-form__full">
                                                                <label>背景颜色</label>
                                                                <div class="ft-page-builder__color-input">
                                                                    <input type="color" id="builderSectionBackgroundPicker" value="#ffffff">
                                                                    <input type="text" id="builderSectionBackground" placeholder="#ffffff">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div id="builderHeadingVisualWrap" style="display: none;">
                                                        <div class="ft-page-builder__field">
                                                            <label>标题内容</label>
                                                            <input type="text" id="builderHeadingText" placeholder="请输入标题">
                                                        </div>
                                                        <div class="ft-page-builder__field-grid">
                                                            <div class="ft-page-builder__field">
                                                                <label>标题级别</label>
                                                                <select id="builderHeadingLevel">
                                                                    <option value="h1">H1</option>
                                                                    <option value="h2">H2</option>
                                                                    <option value="h3">H3</option>
                                                                    <option value="h4">H4</option>
                                                                    <option value="h5">H5</option>
                                                                    <option value="h6">H6</option>
                                                                </select>
                                                            </div>
                                                            <div class="ft-page-builder__field">
                                                                <label>对齐方式</label>
                                                                <select id="builderHeadingAlign">
                                                                    <option value="">默认</option>
                                                                    <option value="left">左对齐</option>
                                                                    <option value="center">居中</option>
                                                                    <option value="right">右对齐</option>
                                                                </select>
                                                            </div>
                                                            <div class="ft-page-builder__field">
                                                                <label>字体颜色</label>
                                                                <div class="ft-page-builder__color-input">
                                                                    <input type="color" id="builderHeadingColorPicker" value="#0f172a">
                                                                    <input type="text" id="builderHeadingColor" placeholder="#0f172a">
                                                                </div>
                                                            </div>
                                                            <div class="ft-page-builder__field">
                                                                <label>字号</label>
                                                                <input type="text" id="builderHeadingFontSize" placeholder="36px">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div id="builderTextVisualWrap" style="display: none;">
                                                        <div class="ft-page-builder__field">
                                                            <label>文本内容</label>
                                                            <textarea id="builderTextContent" placeholder="请输入正文内容"></textarea>
                                                        </div>
                                                        <div class="ft-page-builder__field-grid">
                                                            <div class="ft-page-builder__field">
                                                                <label>对齐方式</label>
                                                                <select id="builderTextAlign">
                                                                    <option value="">默认</option>
                                                                    <option value="left">左对齐</option>
                                                                    <option value="center">居中</option>
                                                                    <option value="right">右对齐</option>
                                                                </select>
                                                            </div>
                                                            <div class="ft-page-builder__field">
                                                                <label>字体颜色</label>
                                                                <div class="ft-page-builder__color-input">
                                                                    <input type="color" id="builderTextColorPicker" value="#475569">
                                                                    <input type="text" id="builderTextColor" placeholder="#475569">
                                                                </div>
                                                            </div>
                                                            <div class="ft-page-builder__field">
                                                                <label>字号</label>
                                                                <input type="text" id="builderTextFontSize" placeholder="16px">
                                                            </div>
                                                            <div class="ft-page-builder__field">
                                                                <label>行高</label>
                                                                <input type="text" id="builderTextLineHeight" placeholder="1.8">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div id="builderButtonVisualWrap" style="display: none;">
                                                        <div class="ft-page-builder__field-grid">
                                                            <div class="ft-page-builder__field">
                                                                <label>按钮文字</label>
                                                                <input type="text" id="builderButtonText" placeholder="了解更多">
                                                            </div>
                                                            <div class="ft-page-builder__field">
                                                                <label>按钮链接</label>
                                                                <input type="text" id="builderButtonHref" placeholder="/about">
                                                            </div>
                                                            <div class="ft-page-builder__field">
                                                                <label>打开方式</label>
                                                                <select id="builderButtonTarget">
                                                                    <option value="">当前窗口</option>
                                                                    <option value="_blank">新窗口</option>
                                                                </select>
                                                            </div>
                                                            <div class="ft-page-builder__field">
                                                                <label>按钮对齐</label>
                                                                <select id="builderButtonAlign">
                                                                    <option value="left">左对齐</option>
                                                                    <option value="center">居中</option>
                                                                    <option value="right">右对齐</option>
                                                                    <option value="full">通栏</option>
                                                                </select>
                                                            </div>
                                                            <div class="ft-page-builder__field">
                                                                <label>按钮样式</label>
                                                                <select id="builderButtonVariant">
                                                                    <option value="solid">实底按钮</option>
                                                                    <option value="outline">边框按钮</option>
                                                                    <option value="ghost">幽灵按钮</option>
                                                                </select>
                                                            </div>
                                                            <div class="ft-page-builder__field">
                                                                <label>背景色</label>
                                                                <div class="ft-page-builder__color-input">
                                                                    <input type="color" id="builderButtonBackgroundPicker" value="#2563eb">
                                                                    <input type="text" id="builderButtonBackground" placeholder="#2563eb">
                                                                </div>
                                                            </div>
                                                            <div class="ft-page-builder__field">
                                                                <label>文字颜色</label>
                                                                <div class="ft-page-builder__color-input">
                                                                    <input type="color" id="builderButtonColorPicker" value="#ffffff">
                                                                    <input type="text" id="builderButtonColor" placeholder="#ffffff">
                                                                </div>
                                                            </div>
                                                            <div class="ft-page-builder__field">
                                                                <label>圆角</label>
                                                                <input type="text" id="builderButtonRadius" placeholder="999px">
                                                            </div>
                                                            <div class="ft-page-builder__field">
                                                                <label>最小高度</label>
                                                                <input type="text" id="builderButtonMinHeight" placeholder="44px">
                                                            </div>
                                                            <div class="ft-page-builder__field">
                                                                <label>边框颜色</label>
                                                                <input type="text" id="builderButtonBorderColor" placeholder="#2563eb">
                                                            </div>
                                                            <div class="ft-page-builder__field ft-page-form__full">
                                                                <label>内边距</label>
                                                                <input type="text" id="builderButtonPadding" placeholder="12px 20px">
                                                            </div>
                                                            <div class="ft-page-builder__field">
                                                                <label>悬浮背景</label>
                                                                <input type="text" id="builderButtonHoverBackground" placeholder="#1d4ed8">
                                                            </div>
                                                            <div class="ft-page-builder__field">
                                                                <label>悬浮文字</label>
                                                                <input type="text" id="builderButtonHoverColor" placeholder="#ffffff">
                                                            </div>
                                                            <div class="ft-page-builder__field">
                                                                <label>悬浮边框</label>
                                                                <input type="text" id="builderButtonHoverBorderColor" placeholder="#1d4ed8">
                                                            </div>
                                                            <div class="ft-page-builder__field ft-page-form__full">
                                                                <label>悬浮阴影</label>
                                                                <input type="text" id="builderButtonHoverShadow" placeholder="0 12px 28px rgba(37, 99, 235, 0.24)">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div id="builderImageVisualWrap" style="display: none;">
                                                        <div class="ft-page-builder__image-tools">
                                                            <div class="ft-page-builder__image-preview" id="builderImagePreview"></div>
                                                            <div class="ft-page-builder__image-actions">
                                                                <button type="button" class="btn btn-default btn-xs" id="builderImageUploadTrigger">上传图片</button>
                                                                <button type="button" class="btn btn-default btn-xs" id="builderImageClear">清空图片</button>
                                                            </div>
                                                            <div class="ft-page-builder__field-help is-status" id="builderImageUploadHint">支持直接上传一张图片并自动回填地址；如果当前页面未绑定模型，会自动回退到第一个可用模型完成上传。</div>
                                                            <input type="file" id="builderImageUploadInput" accept="image/*" style="display: none;">
                                                        </div>
                                                        <div class="ft-page-builder__field-grid">
                                                            <div class="ft-page-builder__field">
                                                                <label>图片来源</label>
                                                                <select id="builderImageSourceType">
                                                                    <option value="manual">手动图片</option>
                                                                    <option value="model_detail">模型详情首图</option>
                                                                </select>
                                                            </div>
                                                            <div class="ft-page-builder__field" id="builderImageModelWrap" style="display: none;">
                                                                <label>绑定模型</label>
                                                                <select id="builderImageModel">
                                                                    <option value="">请选择模型</option>
                                                                    @foreach($models as $model)
                                                                        <option value="{{$model->identification}}">{{$model->name}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="ft-page-builder__field" id="builderImageRecordIdWrap" style="display: none;">
                                                                <label>详情 ID</label>
                                                                <input type="number" min="0" id="builderImageRecordId" placeholder="留空取最新一条">
                                                            </div>
                                                            <div class="ft-page-builder__field" id="builderImageFieldWrap" style="display: none;">
                                                                <label>图片字段</label>
                                                                <input type="text" id="builderImageField" placeholder="cover">
                                                            </div>
                                                            <div class="ft-page-builder__field ft-page-form__full">
                                                                <label>图片地址</label>
                                                                <input type="text" id="builderImageSrc" placeholder="/uploads/demo.jpg">
                                                            </div>
                                                            <div class="ft-page-builder__field ft-page-form__full">
                                                                <label>替代文本</label>
                                                                <input type="text" id="builderImageAlt" placeholder="图片说明">
                                                            </div>
                                                            <div class="ft-page-builder__field">
                                                                <label>宽度</label>
                                                                <input type="text" id="builderImageWidth" placeholder="100%">
                                                            </div>
                                                            <div class="ft-page-builder__field">
                                                                <label>圆角</label>
                                                                <input type="text" id="builderImageRadius" placeholder="20px">
                                                            </div>
                                                            <div class="ft-page-builder__field">
                                                                <label>显示模式</label>
                                                                <select id="builderImageObjectFit">
                                                                    <option value="">默认</option>
                                                                    <option value="cover">cover</option>
                                                                    <option value="contain">contain</option>
                                                                </select>
                                                            </div>
                                                            <div class="ft-page-builder__field">
                                                                <label>图片对齐</label>
                                                                <select id="builderImageAlign">
                                                                    <option value="left">左对齐</option>
                                                                    <option value="center">居中</option>
                                                                    <option value="right">右对齐</option>
                                                                </select>
                                                            </div>
                                                            <div class="ft-page-builder__field">
                                                                <label>最小高度</label>
                                                                <input type="text" id="builderImageMinHeight" placeholder="240px">
                                                            </div>
                                                            <div class="ft-page-builder__field">
                                                                <label>悬浮缩放</label>
                                                                <select id="builderImageHoverScale">
                                                                    <option value="">无</option>
                                                                    <option value="scale(1.03)">轻微放大</option>
                                                                    <option value="scale(1.06)">明显放大</option>
                                                                </select>
                                                            </div>
                                                            <div class="ft-page-builder__field ft-page-form__full">
                                                                <label>悬浮阴影</label>
                                                                <input type="text" id="builderImageHoverShadow" placeholder="0 18px 36px rgba(15, 23, 42, 0.18)">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div id="builderDividerVisualWrap" style="display: none;">
                                                        <div class="ft-page-builder__field-grid">
                                                            <div class="ft-page-builder__field">
                                                                <label>线条颜色</label>
                                                                <input type="text" id="builderDividerColor" placeholder="#e5e7eb">
                                                            </div>
                                                            <div class="ft-page-builder__field">
                                                                <label>线条粗细</label>
                                                                <input type="text" id="builderDividerThickness" placeholder="1px">
                                                            </div>
                                                            <div class="ft-page-builder__field">
                                                                <label>线条样式</label>
                                                                <select id="builderDividerStyle">
                                                                    <option value="solid">实线</option>
                                                                    <option value="dashed">虚线</option>
                                                                    <option value="dotted">点线</option>
                                                                </select>
                                                            </div>
                                                            <div class="ft-page-builder__field">
                                                                <label>宽度</label>
                                                                <input type="text" id="builderDividerWidth" placeholder="100%">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div id="builderHtmlVisualWrap" style="display: none;">
                                                        <div class="ft-page-builder__field">
                                                            <label>HTML 内容</label>
                                                            <textarea id="builderHtmlContent" placeholder="<div class='hero'>自定义结构</div>"></textarea>
                                                            <div class="ft-page-builder__field-help is-static">适合临时承接复杂结构；后面可再拆成标准组件。</div>
                                                        </div>
                                                    </div>

                                                    <div id="builderCarouselVisualWrap" style="display: none;">
                                                        <div class="ft-page-builder__field-grid">
                                                            <div class="ft-page-builder__field">
                                                                <label>数据来源</label>
                                                                <select id="builderCarouselSourceType">
                                                                    <option value="manual">手动轮播项</option>
                                                                    <option value="model_list">模型列表</option>
                                                                </select>
                                                            </div>
                                                            <div class="ft-page-builder__field">
                                                                <label>自动播放</label>
                                                                <select id="builderCarouselAutoplay">
                                                                    <option value="1">开启</option>
                                                                    <option value="0">关闭</option>
                                                                </select>
                                                            </div>
                                                            <div class="ft-page-builder__field">
                                                                <label>切换间隔</label>
                                                                <input type="number" min="1000" step="500" id="builderCarouselInterval" placeholder="5000">
                                                            </div>
                                                            <div class="ft-page-builder__field">
                                                                <label>按钮文案</label>
                                                                <input type="text" id="builderCarouselButtonText" placeholder="立即咨询">
                                                            </div>
                                                            <div class="ft-page-builder__field">
                                                                <label>按钮链接</label>
                                                                <input type="text" id="builderCarouselButtonHref" placeholder="/contact">
                                                            </div>
                                                            <div class="ft-page-builder__field" id="builderCarouselModelWrap" style="display: none;">
                                                                <label>绑定模型</label>
                                                                <select id="builderCarouselModel">
                                                                    <option value="">请选择模型</option>
                                                                    @foreach($models as $model)
                                                                        <option value="{{$model->identification}}">{{$model->name}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="ft-page-builder__field" id="builderCarouselLimitWrap" style="display: none;">
                                                                <label>拉取数量</label>
                                                                <input type="number" min="1" max="12" id="builderCarouselLimit" placeholder="3">
                                                            </div>
                                                            <div class="ft-page-builder__field" id="builderCarouselTitleFieldWrap" style="display: none;">
                                                                <label>标题字段</label>
                                                                <input type="text" id="builderCarouselTitleField" placeholder="title">
                                                            </div>
                                                            <div class="ft-page-builder__field" id="builderCarouselSummaryFieldWrap" style="display: none;">
                                                                <label>摘要字段</label>
                                                                <input type="text" id="builderCarouselSummaryField" placeholder="summary">
                                                            </div>
                                                            <div class="ft-page-builder__field" id="builderCarouselImageFieldWrap" style="display: none;">
                                                                <label>图片字段</label>
                                                                <input type="text" id="builderCarouselImageField" placeholder="cover">
                                                            </div>
                                                            <div class="ft-page-builder__field" id="builderCarouselUrlFieldWrap" style="display: none;">
                                                                <label>链接字段</label>
                                                                <input type="text" id="builderCarouselUrlField" placeholder="url">
                                                            </div>
                                                            <div class="ft-page-builder__field ft-page-form__full" id="builderCarouselDetailPrefixWrap" style="display: none;">
                                                                <label>详情前缀</label>
                                                                <input type="text" id="builderCarouselDetailPrefix" placeholder="/news">
                                                            </div>
                                                            <div class="ft-page-builder__field ft-page-form__full" id="builderCarouselSlidesWrap">
                                                                <label>手动轮播项</label>
                                                                <div class="ft-page-builder__list-editor" id="builderCarouselSlidesEditor"></div>
                                                                <div class="ft-page-builder__list-actions">
                                                                    <button type="button" class="btn btn-default btn-xs" id="builderCarouselSlideAdd">新增轮播项</button>
                                                                </div>
                                                                <details class="ft-page-builder__raw-editor">
                                                                    <summary>文本模式</summary>
                                                                    <div class="ft-page-builder__raw-editor-body">
                                                                        <textarea id="builderCarouselSlides" placeholder="标题|描述|图片地址|按钮文案|按钮链接&#10;第二屏标题|第二屏描述|https://example.com/slide.jpg|查看详情|/detail"></textarea>
                                                                        <div class="ft-page-builder__field-help is-static">一行一项，格式：`标题|描述|图片地址|按钮文案|按钮链接`。</div>
                                                                    </div>
                                                                </details>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div id="builderVideoVisualWrap" style="display: none;">
                                                        <div class="ft-page-builder__field-grid">
                                                            <div class="ft-page-builder__field">
                                                                <label>视频来源</label>
                                                                <select id="builderVideoSourceType">
                                                                    <option value="embed">嵌入地址</option>
                                                                    <option value="mp4">MP4 直链</option>
                                                                </select>
                                                            </div>
                                                            <div class="ft-page-builder__field">
                                                                <label>标题</label>
                                                                <input type="text" id="builderVideoTitle" placeholder="视频介绍">
                                                            </div>
                                                            <div class="ft-page-builder__field ft-page-form__full" id="builderVideoEmbedUrlWrap">
                                                                <label>嵌入地址</label>
                                                                <input type="text" id="builderVideoEmbedUrl" placeholder="https://www.youtube.com/embed/...">
                                                            </div>
                                                            <div class="ft-page-builder__field ft-page-form__full" id="builderVideoMp4UrlWrap" style="display: none;">
                                                                <label>MP4 地址</label>
                                                                <input type="text" id="builderVideoMp4Url" placeholder="https://example.com/demo.mp4">
                                                            </div>
                                                            <div class="ft-page-builder__field">
                                                                <label>封面图</label>
                                                                <input type="text" id="builderVideoPoster" placeholder="https://example.com/poster.jpg">
                                                            </div>
                                                            <div class="ft-page-builder__field">
                                                                <label>比例</label>
                                                                <input type="text" id="builderVideoAspectRatio" placeholder="16:9">
                                                            </div>
                                                            <div class="ft-page-builder__field">
                                                                <label>播放器控件</label>
                                                                <select id="builderVideoControls">
                                                                    <option value="1">显示</option>
                                                                    <option value="0">隐藏</option>
                                                                </select>
                                                            </div>
                                                            <div class="ft-page-builder__field">
                                                                <label>自动播放</label>
                                                                <select id="builderVideoAutoplay">
                                                                    <option value="0">关闭</option>
                                                                    <option value="1">开启</option>
                                                                </select>
                                                            </div>
                                                            <div class="ft-page-builder__field">
                                                                <label>静音</label>
                                                                <select id="builderVideoMuted">
                                                                    <option value="0">关闭</option>
                                                                    <option value="1">开启</option>
                                                                </select>
                                                            </div>
                                                            <div class="ft-page-builder__field">
                                                                <label>循环播放</label>
                                                                <select id="builderVideoLoop">
                                                                    <option value="0">关闭</option>
                                                                    <option value="1">开启</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div id="builderGalleryVisualWrap" style="display: none;">
                                                        <div class="ft-page-builder__field-grid">
                                                            <div class="ft-page-builder__field">
                                                                <label>模块标题</label>
                                                                <input type="text" id="builderGalleryTitle" placeholder="案例图库">
                                                            </div>
                                                            <div class="ft-page-builder__field">
                                                                <label>列数</label>
                                                                <input type="number" min="2" max="6" id="builderGalleryColumns" placeholder="3">
                                                            </div>
                                                            <div class="ft-page-builder__field">
                                                                <label>数据来源</label>
                                                                <select id="builderGallerySourceType">
                                                                    <option value="manual">手动图片项</option>
                                                                    <option value="model_list">模型列表</option>
                                                                </select>
                                                            </div>
                                                            <div class="ft-page-builder__field">
                                                                <label>卡片间距</label>
                                                                <input type="text" id="builderGalleryGap" placeholder="18px">
                                                            </div>
                                                            <div class="ft-page-builder__field ft-page-form__full">
                                                                <label>说明文案</label>
                                                                <input type="text" id="builderGallerySubtitle" placeholder="支持手动图片列表，也支持从模型列表拉取封面图。">
                                                            </div>
                                                            <div class="ft-page-builder__field" id="builderGalleryModelWrap" style="display: none;">
                                                                <label>绑定模型</label>
                                                                <select id="builderGalleryModel">
                                                                    <option value="">请选择模型</option>
                                                                    @foreach($models as $model)
                                                                        <option value="{{$model->identification}}">{{$model->name}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="ft-page-builder__field" id="builderGalleryLimitWrap" style="display: none;">
                                                                <label>拉取数量</label>
                                                                <input type="number" min="1" max="12" id="builderGalleryLimit" placeholder="6">
                                                            </div>
                                                            <div class="ft-page-builder__field" id="builderGalleryTitleFieldWrap" style="display: none;">
                                                                <label>标题字段</label>
                                                                <input type="text" id="builderGalleryTitleField" placeholder="title">
                                                            </div>
                                                            <div class="ft-page-builder__field" id="builderGalleryImageFieldWrap" style="display: none;">
                                                                <label>图片字段</label>
                                                                <input type="text" id="builderGalleryImageField" placeholder="cover">
                                                            </div>
                                                            <div class="ft-page-builder__field" id="builderGalleryUrlFieldWrap" style="display: none;">
                                                                <label>链接字段</label>
                                                                <input type="text" id="builderGalleryUrlField" placeholder="url">
                                                            </div>
                                                            <div class="ft-page-builder__field ft-page-form__full" id="builderGalleryDetailPrefixWrap" style="display: none;">
                                                                <label>详情前缀</label>
                                                                <input type="text" id="builderGalleryDetailPrefix" placeholder="/cases">
                                                            </div>
                                                            <div class="ft-page-builder__field ft-page-form__full" id="builderGalleryItemsWrap">
                                                                <label>手动图片项</label>
                                                                <div class="ft-page-builder__list-editor" id="builderGalleryItemsEditor"></div>
                                                                <div class="ft-page-builder__list-actions">
                                                                    <button type="button" class="btn btn-default btn-xs" id="builderGalleryItemAdd">新增图片项</button>
                                                                </div>
                                                                <details class="ft-page-builder__raw-editor">
                                                                    <summary>文本模式</summary>
                                                                    <div class="ft-page-builder__raw-editor-body">
                                                                        <textarea id="builderGalleryItems" placeholder="案例一|https://example.com/case-01.jpg|/cases/1&#10;案例二|https://example.com/case-02.jpg|/cases/2"></textarea>
                                                                        <div class="ft-page-builder__field-help is-static">一行一项，格式：`标题|图片地址|链接`。</div>
                                                                    </div>
                                                                </details>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div id="builderFaqVisualWrap" style="display: none;">
                                                        <div class="ft-page-builder__field-grid">
                                                            <div class="ft-page-builder__field">
                                                                <label>模块标题</label>
                                                                <input type="text" id="builderFaqTitle" placeholder="常见问题">
                                                            </div>
                                                            <div class="ft-page-builder__field">
                                                                <label>列数</label>
                                                                <select id="builderFaqColumns">
                                                                    <option value="1">单列</option>
                                                                    <option value="2">双列</option>
                                                                </select>
                                                            </div>
                                                            <div class="ft-page-builder__field ft-page-form__full">
                                                                <label>引导说明</label>
                                                                <input type="text" id="builderFaqIntro" placeholder="把用户最关心的问题集中放在这里，减少重复沟通。">
                                                            </div>
                                                            <div class="ft-page-builder__field ft-page-form__full">
                                                                <label>问题项</label>
                                                                <div class="ft-page-builder__list-editor" id="builderFaqItemsEditor"></div>
                                                                <div class="ft-page-builder__list-actions">
                                                                    <button type="button" class="btn btn-default btn-xs" id="builderFaqItemAdd">新增问题项</button>
                                                                </div>
                                                                <details class="ft-page-builder__raw-editor">
                                                                    <summary>文本模式</summary>
                                                                    <div class="ft-page-builder__raw-editor-body">
                                                                        <textarea id="builderFaqItems" placeholder="多久可以上线？|常规营销页可以先搭结构，再补真实内容，通常能较快落地。&#10;支持模型数据吗？|当前已支持部分基础组件的模型来源，详情型组件后续继续扩展。"></textarea>
                                                                        <div class="ft-page-builder__field-help is-static">一行一项，格式：`问题|答案`。</div>
                                                                    </div>
                                                                </details>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div id="builderStatsVisualWrap" style="display: none;">
                                                        <div class="ft-page-builder__field-grid">
                                                            <div class="ft-page-builder__field">
                                                                <label>模块标题</label>
                                                                <input type="text" id="builderStatsTitle" placeholder="核心数据">
                                                            </div>
                                                            <div class="ft-page-builder__field">
                                                                <label>列数</label>
                                                                <input type="number" min="2" max="6" id="builderStatsColumns" placeholder="4">
                                                            </div>
                                                            <div class="ft-page-builder__field ft-page-form__full">
                                                                <label>引导说明</label>
                                                                <input type="text" id="builderStatsIntro" placeholder="用一组高亮数字快速建立信任感。">
                                                            </div>
                                                            <div class="ft-page-builder__field ft-page-form__full">
                                                                <label>指标项</label>
                                                                <div class="ft-page-builder__list-editor" id="builderStatsItemsEditor"></div>
                                                                <div class="ft-page-builder__list-actions">
                                                                    <button type="button" class="btn btn-default btn-xs" id="builderStatsItemAdd">新增指标项</button>
                                                                </div>
                                                                <details class="ft-page-builder__raw-editor">
                                                                    <summary>文本模式</summary>
                                                                    <div class="ft-page-builder__raw-editor-body">
                                                                        <textarea id="builderStatsItems" placeholder="服务客户|2580|+|覆盖多行业项目&#10;顾问响应|15|min|工作时间内快速响应"></textarea>
                                                                        <div class="ft-page-builder__field-help is-static">一行一项，格式：`标签|数值|后缀|说明`。</div>
                                                                    </div>
                                                                </details>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div id="builderCtaVisualWrap" style="display: none;">
                                                        <div class="ft-page-builder__field-grid">
                                                            <div class="ft-page-builder__field">
                                                                <label>眉标题</label>
                                                                <input type="text" id="builderCtaEyebrow" placeholder="Ready To Launch">
                                                            </div>
                                                            <div class="ft-page-builder__field">
                                                                <label>内容对齐</label>
                                                                <select id="builderCtaAlign">
                                                                    <option value="left">左对齐</option>
                                                                    <option value="center">居中</option>
                                                                </select>
                                                            </div>
                                                            <div class="ft-page-builder__field ft-page-form__full">
                                                                <label>主标题</label>
                                                                <input type="text" id="builderCtaTitle" placeholder="把你的商业页面快速搭起来">
                                                            </div>
                                                            <div class="ft-page-builder__field ft-page-form__full">
                                                                <label>说明文案</label>
                                                                <textarea id="builderCtaDescription" placeholder="统一内容、样式和动效配置，减少重复搭建成本。"></textarea>
                                                            </div>
                                                            <div class="ft-page-builder__field">
                                                                <label>主按钮文案</label>
                                                                <input type="text" id="builderCtaPrimaryText" placeholder="立即咨询">
                                                            </div>
                                                            <div class="ft-page-builder__field">
                                                                <label>主按钮链接</label>
                                                                <input type="text" id="builderCtaPrimaryHref" placeholder="/contact">
                                                            </div>
                                                            <div class="ft-page-builder__field">
                                                                <label>次按钮文案</label>
                                                                <input type="text" id="builderCtaSecondaryText" placeholder="查看案例">
                                                            </div>
                                                            <div class="ft-page-builder__field">
                                                                <label>次按钮链接</label>
                                                                <input type="text" id="builderCtaSecondaryHref" placeholder="/cases">
                                                            </div>
                                                            <div class="ft-page-builder__field">
                                                                <label>按钮对齐</label>
                                                                <select id="builderCtaActionsAlign">
                                                                    <option value="left">左对齐</option>
                                                                    <option value="center">居中</option>
                                                                    <option value="right">右对齐</option>
                                                                </select>
                                                            </div>
                                                            <div class="ft-page-builder__field">
                                                                <label>主按钮样式</label>
                                                                <select id="builderCtaPrimaryVariant">
                                                                    <option value="solid">实底按钮</option>
                                                                    <option value="outline">边框按钮</option>
                                                                    <option value="ghost">幽灵按钮</option>
                                                                </select>
                                                            </div>
                                                            <div class="ft-page-builder__field">
                                                                <label>次按钮样式</label>
                                                                <select id="builderCtaSecondaryVariant">
                                                                    <option value="ghost">幽灵按钮</option>
                                                                    <option value="outline">边框按钮</option>
                                                                    <option value="solid">实底按钮</option>
                                                                </select>
                                                            </div>
                                                            <div class="ft-page-builder__field">
                                                                <label>按钮最小高度</label>
                                                                <input type="text" id="builderCtaButtonMinHeight" placeholder="46px">
                                                            </div>
                                                            <div class="ft-page-builder__field">
                                                                <label>悬浮背景</label>
                                                                <input type="text" id="builderCtaHoverBackground" placeholder="#ffffff">
                                                            </div>
                                                            <div class="ft-page-builder__field">
                                                                <label>悬浮文字</label>
                                                                <input type="text" id="builderCtaHoverColor" placeholder="#0f172a">
                                                            </div>
                                                            <div class="ft-page-builder__field">
                                                                <label>悬浮边框</label>
                                                                <input type="text" id="builderCtaHoverBorderColor" placeholder="#ffffff">
                                                            </div>
                                                            <div class="ft-page-builder__field ft-page-form__full">
                                                                <label>悬浮阴影</label>
                                                                <input type="text" id="builderCtaHoverShadow" placeholder="0 16px 36px rgba(15, 23, 42, 0.28)">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div id="builderModelListVisualWrap" style="display: none;">
                                                        <div class="ft-page-builder__field-preset-bar" id="builderModelListPresetBar">
                                                            <button type="button" class="btn btn-default btn-xs" data-model-preset="model_list:article">文章列表</button>
                                                            <button type="button" class="btn btn-default btn-xs" data-model-preset="model_list:product">产品列表</button>
                                                            <button type="button" class="btn btn-default btn-xs" data-model-preset="model_list:news">资讯卡片</button>
                                                        </div>
                                                        <div class="ft-page-builder__field-grid">
                                                            <div class="ft-page-builder__field">
                                                                <label>模块标题</label>
                                                                <input type="text" id="builderModelListTitle" placeholder="模型列表">
                                                            </div>
                                                            <div class="ft-page-builder__field">
                                                                <label>展示模板</label>
                                                                <select id="builderModelListTemplate">
                                                                    <option value="card">卡片</option>
                                                                    <option value="list">列表</option>
                                                                </select>
                                                            </div>
                                                            <div class="ft-page-builder__field">
                                                                <label>绑定模型</label>
                                                                <select id="builderModelListModel">
                                                                    <option value="">请选择模型</option>
                                                                    @foreach($models as $model)
                                                                        <option value="{{$model->identification}}">{{$model->name}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="ft-page-builder__field">
                                                                <label>拉取数量</label>
                                                                <input type="number" min="1" max="24" id="builderModelListLimit" placeholder="6">
                                                            </div>
                                                            <div class="ft-page-builder__field">
                                                                <label>排序字段</label>
                                                                <input type="text" id="builderModelListOrderBy" placeholder="created_at">
                                                            </div>
                                                            <div class="ft-page-builder__field">
                                                                <label>排序方向</label>
                                                                <select id="builderModelListOrderDirection">
                                                                    <option value="desc">倒序</option>
                                                                    <option value="asc">正序</option>
                                                                </select>
                                                            </div>
                                                            <div class="ft-page-builder__field">
                                                                <label>标题字段</label>
                                                                <input type="text" id="builderModelListTitleField" placeholder="title">
                                                            </div>
                                                            <div class="ft-page-builder__field">
                                                                <label>摘要字段</label>
                                                                <input type="text" id="builderModelListSummaryField" placeholder="summary">
                                                            </div>
                                                            <div class="ft-page-builder__field">
                                                                <label>图片字段</label>
                                                                <input type="text" id="builderModelListImageField" placeholder="cover">
                                                            </div>
                                                            <div class="ft-page-builder__field">
                                                                <label>日期字段</label>
                                                                <input type="text" id="builderModelListDateField" placeholder="created_at">
                                                            </div>
                                                            <div class="ft-page-builder__field">
                                                                <label>链接字段</label>
                                                                <input type="text" id="builderModelListUrlField" placeholder="url">
                                                            </div>
                                                            <div class="ft-page-builder__field">
                                                                <label>详情前缀</label>
                                                                <input type="text" id="builderModelListDetailPrefix" placeholder="/news">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div id="builderModelDetailVisualWrap" style="display: none;">
                                                        <div class="ft-page-builder__field-preset-bar" id="builderModelDetailPresetBar">
                                                            <button type="button" class="btn btn-default btn-xs" data-model-preset="model_detail:article">文章详情</button>
                                                            <button type="button" class="btn btn-default btn-xs" data-model-preset="model_detail:product">产品详情</button>
                                                            <button type="button" class="btn btn-default btn-xs" data-model-preset="model_detail:profile">介绍页</button>
                                                        </div>
                                                        <div class="ft-page-builder__field-grid">
                                                            <div class="ft-page-builder__field">
                                                                <label>模块标题</label>
                                                                <input type="text" id="builderModelDetailTitle" placeholder="模型详情">
                                                            </div>
                                                            <div class="ft-page-builder__field">
                                                                <label>展示模板</label>
                                                                <select id="builderModelDetailTemplate">
                                                                    <option value="detail">详情</option>
                                                                    <option value="article">文章</option>
                                                                </select>
                                                            </div>
                                                            <div class="ft-page-builder__field">
                                                                <label>绑定模型</label>
                                                                <select id="builderModelDetailModel">
                                                                    <option value="">请选择模型</option>
                                                                    @foreach($models as $model)
                                                                        <option value="{{$model->identification}}">{{$model->name}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="ft-page-builder__field">
                                                                <label>详情 ID</label>
                                                                <input type="number" min="0" id="builderModelDetailRecordId" placeholder="留空取最新一条">
                                                            </div>
                                                            <div class="ft-page-builder__field">
                                                                <label>标题字段</label>
                                                                <input type="text" id="builderModelDetailTitleField" placeholder="title">
                                                            </div>
                                                            <div class="ft-page-builder__field">
                                                                <label>摘要字段</label>
                                                                <input type="text" id="builderModelDetailSummaryField" placeholder="summary">
                                                            </div>
                                                            <div class="ft-page-builder__field">
                                                                <label>正文字段</label>
                                                                <input type="text" id="builderModelDetailContentField" placeholder="content">
                                                            </div>
                                                            <div class="ft-page-builder__field">
                                                                <label>图片字段</label>
                                                                <input type="text" id="builderModelDetailImageField" placeholder="cover">
                                                            </div>
                                                            <div class="ft-page-builder__field">
                                                                <label>日期字段</label>
                                                                <input type="text" id="builderModelDetailDateField" placeholder="created_at">
                                                            </div>
                                                            <div class="ft-page-builder__field">
                                                                <label>链接字段</label>
                                                                <input type="text" id="builderModelDetailUrlField" placeholder="url">
                                                            </div>
                                                            <div class="ft-page-builder__field">
                                                                <label>详情前缀</label>
                                                                <input type="text" id="builderModelDetailDetailPrefix" placeholder="/about">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div id="builderNavigationVisualWrap" style="display: none;">
                                                        <div class="ft-page-builder__field-grid">
                                                            <div class="ft-page-builder__field">
                                                                <label>导航标题</label>
                                                                <input type="text" id="builderNavigationTitle" placeholder="站点导航">
                                                            </div>
                                                            <div class="ft-page-builder__field">
                                                                <label>品牌形式</label>
                                                                <select id="builderNavigationLogoType">
                                                                    <option value="text">文字</option>
                                                                    <option value="image">图片</option>
                                                                    <option value="svg">SVG</option>
                                                                    <option value="image_text">图片 + 文字</option>
                                                                </select>
                                                            </div>
                                                            <div class="ft-page-builder__field">
                                                                <label>布局方向</label>
                                                                <select id="builderNavigationLayout">
                                                                    <option value="horizontal">横向</option>
                                                                    <option value="vertical">纵向</option>
                                                                </select>
                                                            </div>
                                                            <div class="ft-page-builder__field">
                                                                <label>品牌链接</label>
                                                                <input type="text" id="builderNavigationBrandHref" placeholder="/ 或 #hero">
                                                            </div>
                                                            <div class="ft-page-builder__field">
                                                                <label>CTA 文字</label>
                                                                <input type="text" id="builderNavigationCtaText" placeholder="立即咨询">
                                                            </div>
                                                            <div class="ft-page-builder__field">
                                                                <label>CTA 链接</label>
                                                                <input type="text" id="builderNavigationCtaHref" placeholder="/contact 或 #contact">
                                                            </div>
                                                            <div class="ft-page-builder__field ft-page-form__full">
                                                                <label>Logo 图片地址</label>
                                                                <input type="text" id="builderNavigationLogoImage" placeholder="/uploads/logo.png">
                                                            </div>
                                                            <div class="ft-page-builder__field ft-page-form__full">
                                                                <label>Logo SVG 代码</label>
                                                                <textarea id="builderNavigationLogoSvg" placeholder="<svg viewBox='0 0 64 64'>...</svg>"></textarea>
                                                                <div class="ft-page-builder__field-help is-static">支持直接粘贴一段 SVG 代码。页内跳转直接填 `#hero`、`#contact` 这类锚点即可。</div>
                                                            </div>
                                                            <div class="ft-page-builder__field ft-page-form__full">
                                                                <label>导航项</label>
                                                                <div class="ft-page-builder__list-editor" id="builderNavigationItemsEditor"></div>
                                                                <div class="ft-page-builder__nav-actions">
                                                                    <button type="button" class="btn btn-default btn-xs" id="builderNavigationItemAdd">新增一级菜单</button>
                                                                </div>
                                                                <details class="ft-page-builder__raw-editor">
                                                                    <summary>文本模式</summary>
                                                                    <div class="ft-page-builder__raw-editor-body">
                                                                        <textarea id="builderNavigationItems" placeholder="首页|#hero&#10;产品|#products&#10;- 产品总览|#products&#10;- 产品定价|#pricing&#10;联系我们|#contact"></textarea>
                                                                        <div class="ft-page-builder__field-help is-static">一级菜单直接写，子菜单在前面加 `- `，格式：`文字|链接`，支持页内锚点。</div>
                                                                    </div>
                                                                </details>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div id="builderSidebarVisualWrap" style="display: none;">
                                                        <div class="ft-page-builder__field-grid">
                                                            <div class="ft-page-builder__field">
                                                                <label>侧栏标题</label>
                                                                <input type="text" id="builderSidebarTitle" placeholder="快捷入口">
                                                            </div>
                                                            <div class="ft-page-builder__field">
                                                                <label>停靠位置</label>
                                                                <select id="builderSidebarPosition">
                                                                    <option value="right">右侧</option>
                                                                    <option value="left">左侧</option>
                                                                </select>
                                                            </div>
                                                            <div class="ft-page-builder__field">
                                                                <label>距顶偏移</label>
                                                                <input type="text" id="builderSidebarOffsetTop" placeholder="120px">
                                                            </div>
                                                            <div class="ft-page-builder__field">
                                                                <label>返回顶部</label>
                                                                <select id="builderSidebarShowBackTop">
                                                                    <option value="1">显示</option>
                                                                    <option value="0">隐藏</option>
                                                                </select>
                                                            </div>
                                                            <div class="ft-page-builder__field ft-page-form__full">
                                                                <label>侧栏项目</label>
                                                                <div class="ft-page-builder__list-editor" id="builderSidebarItemsEditor"></div>
                                                                <div class="ft-page-builder__nav-actions">
                                                                    <button type="button" class="btn btn-default btn-xs" id="builderSidebarItemAdd">新增栏位</button>
                                                                </div>
                                                                <details class="ft-page-builder__raw-editor">
                                                                    <summary>文本模式</summary>
                                                                    <div class="ft-page-builder__raw-editor-body">
                                                                        <textarea id="builderSidebarItems" placeholder="在线咨询|#contact|咨|link|custom|||||#ffffff|#2563eb|#bfdbfe&#10;微信咨询||微|panel|qrcode|扫码咨询|添加顾问获取方案|https://example.com/wechat||#0f172a|#ffffff|rgba(148,163,184,.22)&#10;服务手册||册|panel|custom|资料领取|支持自定义 HTML 面板|/uploads/sidebar-brochure.jpg|<div><h4>活动资料</h4><p>这里可放富文本、自定义 HTML、按钮等内容。</p></div>|#eff6ff|#1d4ed8|#bfdbfe"></textarea>
                                                                        <div class="ft-page-builder__field-help is-static">格式：`标题|链接|图标源|动作|面板类型|面板标题|面板内容|二维码值/扩展值|HTML 内容|背景色|文字色|边框色`。动作支持 `link` / `panel`，面板类型支持 `qrcode` / `custom`。</div>
                                                                    </div>
                                                                </details>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div id="builderQrCodeVisualWrap" style="display: none;">
                                                        <div class="ft-page-builder__field-grid">
                                                            <div class="ft-page-builder__field">
                                                                <label>二维码标题</label>
                                                                <input type="text" id="builderQrCodeTitle" placeholder="扫码咨询">
                                                            </div>
                                                            <div class="ft-page-builder__field">
                                                                <label>二维码尺寸</label>
                                                                <input type="number" min="96" max="320" id="builderQrCodeSize" placeholder="140">
                                                            </div>
                                                            <div class="ft-page-builder__field ft-page-form__full">
                                                                <label>二维码内容</label>
                                                                <input type="text" id="builderQrCodeValue" placeholder="https://example.com/contact">
                                                            </div>
                                                            <div class="ft-page-builder__field ft-page-form__full">
                                                                <label>说明文案</label>
                                                                <input type="text" id="builderQrCodeText" placeholder="微信扫码，获取专属顾问服务">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div id="builderLoginVisualWrap" style="display: none;">
                                                        <div class="ft-page-builder__field-grid">
                                                            <div class="ft-page-builder__field">
                                                                <label>模块标题</label>
                                                                <input type="text" id="builderLoginTitle" placeholder="账号入口">
                                                            </div>
                                                            <div class="ft-page-builder__field">
                                                                <label>登录按钮文案</label>
                                                                <input type="text" id="builderLoginButtonText" placeholder="立即登录">
                                                            </div>
                                                            <div class="ft-page-builder__field">
                                                                <label>登录地址</label>
                                                                <input type="text" id="builderLoginAction" placeholder="/login">
                                                            </div>
                                                            <div class="ft-page-builder__field">
                                                                <label>已登录文案</label>
                                                                <input type="text" id="builderLoginProfileText" placeholder="个人中心">
                                                            </div>
                                                            <div class="ft-page-builder__field">
                                                                <label>已登录跳转</label>
                                                                <input type="text" id="builderLoginProfileHref" placeholder="/member">
                                                            </div>
                                                            <div class="ft-page-builder__field ft-page-form__full">
                                                                <label>自定义头像地址</label>
                                                                <input type="text" id="builderLoginAvatarUrl" placeholder="/uploads/avatar.png">
                                                                <div class="ft-page-builder__field-help is-static">留空时优先读取当前登录用户头像，没有头像则显示首字母。</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="ft-page-builder__subpanel" id="builderVisibilityConfig">
                                                    <h5 class="ft-page-builder__subpanel-title">显示隐藏条件</h5>
                                                    <p class="ft-page-builder__subpanel-desc">按登录、参数、设备控制显示。</p>
                                                    <div class="ft-page-builder__field-grid">
                                                        <div class="ft-page-builder__field">
                                                            <label>条件模式</label>
                                                            <select id="builderVisibilityEffect">
                                                                <option value="always">始终显示</option>
                                                                <option value="show">命中条件时显示</option>
                                                                <option value="hide">命中条件时隐藏</option>
                                                            </select>
                                                        </div>
                                                        <div class="ft-page-builder__field">
                                                            <label>条件类型</label>
                                                            <select id="builderVisibilityRule">
                                                                <option value="logged_in">已登录</option>
                                                                <option value="guest">未登录</option>
                                                                <option value="url_param">URL 参数</option>
                                                                <option value="device">访问设备</option>
                                                            </select>
                                                        </div>
                                                        <div class="ft-page-builder__field">
                                                            <label>条件关系</label>
                                                            <select id="builderVisibilityLogic">
                                                                <option value="all">同时满足</option>
                                                                <option value="any">任一满足</option>
                                                            </select>
                                                        </div>
                                                        <div class="ft-page-builder__field">
                                                            <label>附加条件</label>
                                                            <select id="builderVisibilityExtraRule">
                                                                <option value="">无</option>
                                                                <option value="logged_in">已登录</option>
                                                                <option value="guest">未登录</option>
                                                                <option value="url_param">URL 参数</option>
                                                                <option value="device">访问设备</option>
                                                            </select>
                                                        </div>
                                                        <div class="ft-page-builder__field" id="builderVisibilityParamWrap" style="display: none;">
                                                            <label>参数名</label>
                                                            <input type="text" id="builderVisibilityParam" placeholder="from">
                                                        </div>
                                                        <div class="ft-page-builder__field" id="builderVisibilityValueWrap" style="display: none;">
                                                            <label>参数值</label>
                                                            <input type="text" id="builderVisibilityValue" placeholder="campaign">
                                                        </div>
                                                        <div class="ft-page-builder__field ft-page-form__full" id="builderVisibilityDeviceWrap" style="display: none;">
                                                            <label>设备范围</label>
                                                            <input type="text" id="builderVisibilityDevices" placeholder="mobile 或 mobile,tablet">
                                                        </div>
                                                        <div class="ft-page-builder__field" id="builderVisibilityExtraParamWrap" style="display: none;">
                                                            <label>附加参数名</label>
                                                            <input type="text" id="builderVisibilityExtraParam" placeholder="channel">
                                                        </div>
                                                        <div class="ft-page-builder__field" id="builderVisibilityExtraValueWrap" style="display: none;">
                                                            <label>附加参数值</label>
                                                            <input type="text" id="builderVisibilityExtraValue" placeholder="app">
                                                        </div>
                                                        <div class="ft-page-builder__field ft-page-form__full" id="builderVisibilityExtraDeviceWrap" style="display: none;">
                                                            <label>附加设备范围</label>
                                                            <input type="text" id="builderVisibilityExtraDevices" placeholder="mobile 或 tablet">
                                                        </div>
                                                    </div>
                                                    <div class="ft-page-builder__field-help is-status" id="builderVisibilityHint">默认始终显示。条件命中后，可选择“显示”或“隐藏”当前区块。</div>
                                                </div>

                                                <div class="ft-page-builder__subpanel" id="builderReusableConfig">
                                                    <h5 class="ft-page-builder__subpanel-title">区块复用</h5>
                                                    <p class="ft-page-builder__subpanel-desc">当前区块可存成本地片段，后面一键复用。</p>
                                                    <div class="ft-page-builder__field">
                                                        <label>复用名称</label>
                                                        <input type="text" id="builderReusableName" placeholder="例如：专题页 CTA、FAQ 单项、主视觉卡片">
                                                    </div>
                                                    <div class="ft-page-builder__subpanel-actions">
                                                        <button type="button" class="btn btn-default btn-sm" id="builderSaveReusable">保存当前区块</button>
                                                    </div>
                                                    <div class="ft-page-builder__field-help is-status" id="builderReusableHint">复用区块保存在当前浏览器里，适合重复搭建同类页面。</div>
                                                    <div class="ft-page-builder__reuse-list" id="builderReusableList"></div>
                                                </div>
                                            </div>

                                                    <div class="ft-page-builder__inspector-panel" data-inspector-panel="style">
                                                <div class="ft-page-builder__subpanel" id="builderStylePresetConfig">
                                                    <h5 class="ft-page-builder__subpanel-title">外观预设</h5>
                                                    <p class="ft-page-builder__subpanel-desc">常用样式先点预设，再细调。</p>
                                                    <div class="ft-page-builder__subpanel-actions" id="builderQuickPresetWrap">
                                                        <div class="ft-page-builder__field-help is-static" id="builderQuickPresetHint">这里会按当前区块类型给出一组常用样式预设，点击即可应用。</div>
                                                        <div class="ft-page-builder__quick-preset-list" id="builderQuickPresetList"></div>
                                                    </div>
                                                    <div class="ft-page-builder__subpanel-actions" id="builderStylePresetWrap">
                                                        <div class="ft-page-builder__field-help is-static" id="builderStylePresetHint">这里会出现颜色块、字号、圆角、边距等可点击样式预设。</div>
                                                        <div class="ft-page-builder__style-presets" id="builderStylePresetList"></div>
                                                    </div>
                                                </div>

                                                <div class="ft-page-builder__subpanel" id="builderCommonStyleConfig">
                                                    <h5 class="ft-page-builder__subpanel-title">通用样式</h5>
                                                    <p class="ft-page-builder__subpanel-desc">高频样式直接改，少回 JSON。</p>
                                                    <div class="ft-page-builder__field-grid">
                                                        <div class="ft-page-builder__field">
                                                            <label for="builderCommonWidth">宽度</label>
                                                            <input type="text" id="builderCommonWidth" placeholder="100%">
                                                        </div>
                                                        <div class="ft-page-builder__field">
                                                            <label for="builderCommonMinHeight">最小高度</label>
                                                            <input type="text" id="builderCommonMinHeight" placeholder="240px">
                                                        </div>
                                                        <div class="ft-page-builder__field">
                                                            <label for="builderCommonRadius">圆角</label>
                                                            <input type="text" id="builderCommonRadius" placeholder="16px">
                                                        </div>
                                                        <div class="ft-page-builder__field">
                                                            <label for="builderCommonBorderWidth">边框粗细</label>
                                                            <input type="text" id="builderCommonBorderWidth" placeholder="1px">
                                                        </div>
                                                        <div class="ft-page-builder__field">
                                                            <label for="builderCommonBorderStyle">边框样式</label>
                                                            <select id="builderCommonBorderStyle">
                                                                <option value="">默认</option>
                                                                <option value="solid">实线</option>
                                                                <option value="dashed">虚线</option>
                                                                <option value="dotted">点线</option>
                                                                <option value="none">无边框</option>
                                                            </select>
                                                        </div>
                                                        <div class="ft-page-builder__field">
                                                            <label for="builderCommonBorderColor">边框颜色</label>
                                                            <input type="text" id="builderCommonBorderColor" placeholder="#e2e8f0">
                                                        </div>
                                                        <div class="ft-page-builder__field ft-page-form__full">
                                                            <label for="builderCommonBoxShadow">阴影</label>
                                                            <input type="text" id="builderCommonBoxShadow" placeholder="0 16px 36px rgba(15, 23, 42, 0.08)">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="ft-page-builder__subpanel" id="builderSpacingConfig">
                                                    <h5 class="ft-page-builder__subpanel-title">间距微调</h5>
                                                    <p class="ft-page-builder__subpanel-desc">上下左右分别填值，支持 px、rem、0。</p>
                                                    <div class="ft-page-builder__field-grid">
                                                        <div class="ft-page-builder__field">
                                                            <div class="ft-page-builder__spacing-title">内边距 Padding</div>
                                                            <div class="ft-page-builder__spacing-grid">
                                                                <input type="text" id="builderPaddingTop" placeholder="上">
                                                                <input type="text" id="builderPaddingRight" placeholder="右">
                                                                <input type="text" id="builderPaddingBottom" placeholder="下">
                                                                <input type="text" id="builderPaddingLeft" placeholder="左">
                                                            </div>
                                                        </div>
                                                        <div class="ft-page-builder__field">
                                                            <div class="ft-page-builder__spacing-title">外边距 Margin</div>
                                                            <div class="ft-page-builder__spacing-grid">
                                                                <input type="text" id="builderMarginTop" placeholder="上">
                                                                <input type="text" id="builderMarginRight" placeholder="右">
                                                                <input type="text" id="builderMarginBottom" placeholder="下">
                                                                <input type="text" id="builderMarginLeft" placeholder="左">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="ft-page-builder__subpanel" id="builderAnimationConfig">
                                                    <h5 class="ft-page-builder__subpanel-title">入场动效</h5>
                                                    <p class="ft-page-builder__subpanel-desc">先补基础市场组件常用动效，后面再扩展更细的触发方式。</p>
                                                    <div class="ft-page-builder__field-grid">
                                                        <div class="ft-page-builder__field">
                                                            <label>动效类型</label>
                                                            <select id="builderAnimationEffect">
                                                                <option value="none">无</option>
                                                                <option value="fade-up">上移淡入</option>
                                                                <option value="fade-in">淡入</option>
                                                                <option value="zoom-in">缩放淡入</option>
                                                            </select>
                                                        </div>
                                                        <div class="ft-page-builder__field">
                                                            <label>时长</label>
                                                            <input type="text" id="builderAnimationDuration" placeholder="0.6s">
                                                        </div>
                                                        <div class="ft-page-builder__field">
                                                            <label>延迟</label>
                                                            <input type="text" id="builderAnimationDelay" placeholder="0s">
                                                        </div>
                                                    </div>
                                                    <div class="ft-page-builder__field-help is-static" id="builderAnimationHint">当前先做页面加载后的基础动效，适合首屏、卡片、图片、轮播和按钮组件。</div>
                                                </div>

                                                <div class="ft-page-builder__subpanel" id="builderContainerConfig" style="display: none;">
                                                    <h5 class="ft-page-builder__subpanel-title">容器布局设置</h5>
                                                    <p class="ft-page-builder__subpanel-desc">容器常用宽度、间距和列宽集中在这里。</p>
                                                    <div class="ft-page-builder__field-grid">
                                                        <div class="ft-page-builder__field">
                                                            <label>容器 class</label>
                                                            <input type="text" id="builderContainerClass" placeholder="例如：hero-section">
                                                        </div>
                                                        <div class="ft-page-builder__field">
                                                            <label>背景色</label>
                                                            <input type="text" id="builderContainerBackground" placeholder="#ffffff">
                                                        </div>
                                                        <div class="ft-page-builder__field">
                                                            <label>内边距</label>
                                                            <input type="text" id="builderContainerPadding" placeholder="48px 0">
                                                        </div>
                                                        <div class="ft-page-builder__field">
                                                            <label>外边距</label>
                                                            <input type="text" id="builderContainerMargin" placeholder="0 0 24px">
                                                        </div>
                                                        <div class="ft-page-builder__field" id="builderRowGapWrap" style="display: none;">
                                                            <label>行间距 gap</label>
                                                            <input type="text" id="builderRowGap" placeholder="20px">
                                                        </div>
                                                        <div class="ft-page-builder__field" id="builderColumnSpanWrap" style="display: none;">
                                                            <label>列宽 span</label>
                                                            <input type="number" min="1" max="12" id="builderColumnSpan" placeholder="6">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                                    <div class="ft-page-builder__inspector-panel" data-inspector-panel="responsive">
                                                <div class="ft-page-builder__subpanel" id="builderResponsiveConfig">
                                                    <h5 class="ft-page-builder__subpanel-title">布局适配</h5>
                                                    <p class="ft-page-builder__subpanel-desc">只调平板和手机的列宽、边距、字号。</p>
                                                    <div class="ft-page-builder__field-grid">
                                                        <div class="ft-page-builder__field">
                                                            <label for="builderTabletPadding">平板内边距</label>
                                                            <input type="text" id="builderTabletPadding" placeholder="32px 20px">
                                                        </div>
                                                        <div class="ft-page-builder__field">
                                                            <label for="builderTabletMargin">平板外边距</label>
                                                            <input type="text" id="builderTabletMargin" placeholder="0 0 20px">
                                                        </div>
                                                        <div class="ft-page-builder__field">
                                                            <label for="builderTabletFontSize">平板字号</label>
                                                            <input type="text" id="builderTabletFontSize" placeholder="16px">
                                                        </div>
                                                        <div class="ft-page-builder__field">
                                                            <label for="builderTabletGap">平板间距</label>
                                                            <input type="text" id="builderTabletGap" placeholder="18px">
                                                        </div>
                                                        <div class="ft-page-builder__field">
                                                            <label for="builderMobilePadding">手机内边距</label>
                                                            <input type="text" id="builderMobilePadding" placeholder="20px 14px">
                                                        </div>
                                                        <div class="ft-page-builder__field">
                                                            <label for="builderMobileMargin">手机外边距</label>
                                                            <input type="text" id="builderMobileMargin" placeholder="0 0 16px">
                                                        </div>
                                                        <div class="ft-page-builder__field">
                                                            <label for="builderMobileFontSize">手机字号</label>
                                                            <input type="text" id="builderMobileFontSize" placeholder="14px">
                                                        </div>
                                                        <div class="ft-page-builder__field">
                                                            <label for="builderMobileGap">手机间距</label>
                                                            <input type="text" id="builderMobileGap" placeholder="14px">
                                                        </div>
                                                    </div>
                                                    <div class="ft-page-builder__field">
                                                        <label>平板 Style JSON</label>
                                                        <textarea id="builderTabletStyle" placeholder='例如：{"padding":"32px 20px"}'></textarea>
                                                    </div>
                                                    <div class="ft-page-builder__field">
                                                        <label>手机 Style JSON</label>
                                                        <textarea id="builderMobileStyle" placeholder='例如：{"padding":"20px 14px","fontSize":"14px"}'></textarea>
                                                    </div>
                                                    <div class="ft-page-builder__field-grid">
                                                        <div class="ft-page-builder__field" id="builderTabletSpanWrap" style="display: none;">
                                                            <label>平板列宽 span</label>
                                                            <input type="number" min="1" max="12" id="builderTabletSpan" placeholder="12">
                                                        </div>
                                                        <div class="ft-page-builder__field" id="builderMobileSpanWrap" style="display: none;">
                                                            <label>手机列宽 span</label>
                                                            <input type="number" min="1" max="12" id="builderMobileSpan" placeholder="12">
                                                        </div>
                                                    </div>
                                                    <div class="ft-page-builder__field-help is-static" id="builderResponsiveHint">适合改边距、字号、列表栅格密度、列宽等。列宽仅对 `column` 区块生效。</div>
                                                </div>
                                            </div>

                                                    <div class="ft-page-builder__inspector-panel" data-inspector-panel="advanced">
                                                <div class="ft-page-builder__subpanel" id="builderAdvancedMetaConfig">
                                                    <h5 class="ft-page-builder__subpanel-title">基础标识</h5>
                                                    <p class="ft-page-builder__subpanel-desc">这里只看路径、类型和区块 ID。</p>
                                                    <div class="ft-page-builder__field">
                                                        <label>区块路径</label>
                                                        <input type="text" id="builderNodePath" readonly>
                                                    </div>
                                                    <div class="ft-page-builder__field">
                                                        <label>区块类型</label>
                                                        <input type="text" id="builderNodeType" readonly>
                                                    </div>
                                                    <div class="ft-page-builder__field">
                                                        <label>区块 ID</label>
                                                        <input type="text" id="builderNodeId" placeholder="例如：hero_section">
                                                    </div>
                                                </div>
                                                <div class="ft-page-builder__subpanel" id="builderJsonConfig">
                                                    <h5 class="ft-page-builder__subpanel-title">JSON 编辑</h5>
                                                    <p class="ft-page-builder__subpanel-desc">复杂属性再展开改 JSON。</p>
                                                    <div class="ft-page-builder__field">
                                                        <label>Props JSON</label>
                                                        <textarea id="builderNodeProps"></textarea>
                                                        <div class="ft-page-builder__field-help is-static" id="builderNodePropsHint">写文本、链接、图片地址等业务属性。</div>
                                                    </div>
                                                    <div class="ft-page-builder__field">
                                                        <label>Style JSON</label>
                                                        <textarea id="builderNodeStyle"></textarea>
                                                        <div class="ft-page-builder__field-help is-static" id="builderNodeStyleHint">写边距、背景、颜色、宽度等展示属性。</div>
                                                    </div>
                                                </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                </div>
                            </div>

                            <div class="ft-page-form__grid">
                                <div class="ft-page-form__full">
                                    <label class="ft-page-form__label">布局 JSON</label>
                                    <textarea name="layout_schema" id="layoutSchema">{{$formData['layout_schema']}}</textarea>
                                    <div class="ft-page-form__help">建议用于 section、row、column、heading、text、html 等区块描述。后续接拖拽编辑器时无需改表结构。</div>
                                </div>
                                <div class="ft-page-form__full">
                                    <label class="ft-page-form__label">页面 HTML</label>
                                    <textarea name="page_html">{{$formData['page_html']}}</textarea>
                                    <div class="ft-page-form__help">可直接写 DIV 布局、组件占位和模型输出容器。当前前台预览会优先渲染这里的内容。</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="ft-page-form__panel">
                        <div class="ft-page-form__panel-header">
                            <div>
                                <h3 class="ft-page-form__panel-title">独立资源与 SEO</h3>
                                <p class="ft-page-form__panel-desc">每张页面都可以带独立 CSS、JS 和 SEO 信息，不再和全站资源混在一起。</p>
                            </div>
                        </div>
                        <div class="ft-page-form__body">
                            <div class="ft-page-form__grid">
                                <div class="ft-page-form__full">
                                    <label class="ft-page-form__label">独立 CSS</label>
                                    <textarea name="custom_css">{{$formData['custom_css']}}</textarea>
                                    <div class="ft-page-form__help">建议把样式作用域约束在当前页面容器，避免影响全站其它页面。</div>
                                </div>
                                <div class="ft-page-form__full">
                                    <label class="ft-page-form__label">独立 JS</label>
                                    <textarea name="custom_js">{{$formData['custom_js']}}</textarea>
                                    <div class="ft-page-form__help">适合当前页面的动效、交互、数据请求，不建议写全站公共逻辑。</div>
                                </div>
                                <div>
                                    <label class="ft-page-form__label">SEO 标题</label>
                                    <input type="text" name="seo_title" value="{{$formData['seo_title']}}" class="ft-page-form__control">
                                </div>
                                <div>
                                    <label class="ft-page-form__label">SEO 关键词</label>
                                    <input type="text" name="seo_keywords" value="{{$formData['seo_keywords']}}" class="ft-page-form__control">
                                </div>
                                <div class="ft-page-form__full">
                                    <label class="ft-page-form__label">SEO 描述</label>
                                    <textarea name="seo_description" style="min-height: 120px;">{{$formData['seo_description']}}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="ft-page-form__panel">
                        <div class="ft-page-form__panel-header">
                            <div>
                                <h3 class="ft-page-form__panel-title">区块协议</h3>
                                <p class="ft-page-form__panel-desc">这部分是给后续 DIV 可视化拖拽编辑器准备的协议参考。先按这些类型存 `layout_schema`，后面直接对接画布和属性面板。</p>
                            </div>
                            <a href="{{url('admin/formtools/pageCategoryList')}}" class="btn btn-default btn-sm">管理页面分类</a>
                        </div>
                        <div class="ft-page-form__body">
                            <div class="ft-page-form__catalog">
                                @foreach($blockCatalog as $block)
                                    <div class="ft-page-form__catalog-item">
                                        <h4 class="ft-page-form__catalog-title">{{$block['name']}}</h4>
                                        <span class="ft-page-form__catalog-meta">{{$block['type']}}</span>
                                        <p class="ft-page-form__catalog-desc">{{$block['desc']}}</p>
                                        <pre class="ft-page-form__catalog-code">{{$block['schema']}}</pre>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="ft-page-form__floating-bar" id="pageFormFloatingBar">
                        <div class="ft-page-form__floating-main">
                            <div class="ft-page-form__floating-title" id="pageFormFloatingTitle">{{$formData['name'] ?: '未命名页面'}}</div>
                            <div class="ft-page-form__floating-meta">
                                <span class="ft-page-form__floating-chip is-success" id="pageFormFloatingStatus">已同步</span>
                                <span class="ft-page-form__floating-chip" id="pageFormFloatingSlug">{{$formData['slug'] ? '路径：'.$formData['slug'] : '路径：待填写'}}</span>
                                <span class="ft-page-form__floating-chip" id="pageFormFloatingMode">{{$formData['builder_type'] === 'html' ? 'HTML 模式' : 'Visual 模式'}}</span>
                                <span class="ft-page-form__floating-chip" id="pageFormFloatingSelected">当前未选中区块</span>
                            </div>
                        </div>
                        <div class="ft-page-form__floating-actions">
                            <a href="{{url('admin/formtools/pageList')}}" class="btn btn-default">返回列表</a>
                            @if($previewUrl)
                                <a href="{{$previewUrl}}" target="_blank" class="btn btn-info">预览页面</a>
                            @endif
                            @if($publicUrl)
                                <a href="{{$publicUrl}}" target="_blank" class="btn btn-default">打开正式页</a>
                            @endif
                            <button type="submit" class="btn btn-primary" id="pageFormFloatingSubmit">保存页面</button>
                        </div>
                    </div>
                </form>

                @include(moduleAdminTemplate($moduleName)."public.footer")
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('views/modules/formtools/assets/page-core.js') }}"></script>
<script>
    (function () {
        var pageFormEditor = document.getElementById('pageFormEditor');
        var fillButton = document.getElementById('fillDefaultSchema');
        var layoutSchema = document.getElementById('layoutSchema');
        var pageNameInput = document.querySelector('input[name="name"]');
        var pageSlugInput = document.querySelector('input[name="slug"]');
        var pageBuilderTypeInputs = [].slice.call(document.querySelectorAll('input[name="builder_type"]'));
        var pageFormWorkspaceName = document.getElementById('pageFormWorkspaceName');
        var pageFormWorkspaceSlug = document.getElementById('pageFormWorkspaceSlug');
        var pageFormWorkspaceMode = document.getElementById('pageFormWorkspaceMode');
        var pageFormWorkspaceStatus = document.getElementById('pageFormWorkspaceStatus');
        var pageFormFloatingTitle = document.getElementById('pageFormFloatingTitle');
        var pageFormFloatingStatus = document.getElementById('pageFormFloatingStatus');
        var pageFormFloatingSlug = document.getElementById('pageFormFloatingSlug');
        var pageFormFloatingMode = document.getElementById('pageFormFloatingMode');
        var pageFormFloatingSelected = document.getElementById('pageFormFloatingSelected');
        var pageFormFloatingSubmit = document.getElementById('pageFormFloatingSubmit');
        var pageBuilderWorkspace = document.getElementById('pageBuilderWorkspace');
        var pageBuilderInspectorTrack = document.getElementById('pageBuilderInspectorTrack');
        var pageBuilderInspectorPanel = pageBuilderWorkspace ? pageBuilderWorkspace.querySelector('.ft-page-builder__panel--inspector') : null;
        var pageBuilderCatalog = document.getElementById('pageBuilderCatalog');
        var pageBuilderCatalogSearch = document.getElementById('pageBuilderCatalogSearch');
        var pageBuilderCatalogFilters = document.getElementById('pageBuilderCatalogFilters');
        var pageBuilderCatalogCurrentTitle = document.getElementById('pageBuilderCatalogCurrentTitle');
        var pageBuilderCatalogCurrentMeta = document.getElementById('pageBuilderCatalogCurrentMeta');
        var pageBuilderCatalogMeta = document.getElementById('pageBuilderCatalogMeta');
        var pageBuilderCatalogQuick = document.getElementById('pageBuilderCatalogQuick');
        var pageBuilderCatalogTarget = document.getElementById('pageBuilderCatalogTarget');
        var pageBuilderCanvas = document.getElementById('pageBuilderCanvas');
        var pageBuilderCanvasStatus = document.getElementById('pageBuilderCanvasStatus');
        var pageBuilderPresetList = document.getElementById('pageBuilderPresetList');
        var pageBuilderTemplateList = document.getElementById('pageBuilderTemplateList');
        var pageBuilderNotice = document.getElementById('pageBuilderNotice');
        var pageBuilderSelectionBreadcrumb = document.getElementById('pageBuilderSelectionBreadcrumb');
        var pageBuilderSelectionBar = document.getElementById('pageBuilderSelectionBar');
        var pageBuilderLivePreview = document.getElementById('pageBuilderLivePreview');
        var pageBuilderLivePreviewNote = document.getElementById('pageBuilderLivePreviewNote');
        var pageBuilderDeviceSwitch = document.getElementById('pageBuilderDeviceSwitch');
        var pageBuilderDeviceMeta = document.getElementById('pageBuilderDeviceMeta');
        var pageBuilderThemeFold = document.getElementById('pageBuilderThemeFold');
        var pageBuilderThemePresets = document.getElementById('pageBuilderThemePresets');
        var pageBuilderThemeReset = document.getElementById('pageBuilderThemeReset');
        var pageBuilderThemeSummary = document.getElementById('pageBuilderThemeSummary');
        var pageBuilderThemeMeta = document.getElementById('pageBuilderThemeMeta');
        var pageBuilderThemeFields = [].slice.call(document.querySelectorAll('[data-theme-field]'));
        var pageBuilderThemeColorPickers = [].slice.call(document.querySelectorAll('[data-theme-color-picker]'));
        var pageBuilderThemePreviews = [].slice.call(document.querySelectorAll('[data-theme-preview]'));
        var pageModelSelect = document.querySelector('select[name="model_id"]');
        var addRootSection = document.getElementById('addRootSection');
        var reloadSchemaCanvas = document.getElementById('reloadSchemaCanvas');
        var inspectorEmpty = document.getElementById('pageBuilderInspectorEmpty');
        var inspectorForm = document.getElementById('pageBuilderInspectorForm');
        var pageBuilderInspectorTabs = document.getElementById('pageBuilderInspectorTabs');
        var pageBuilderInspectorStickyActions = document.getElementById('pageBuilderInspectorStickyActions');
        var builderNodePath = document.getElementById('builderNodePath');
        var builderNodeType = document.getElementById('builderNodeType');
        var builderNodeId = document.getElementById('builderNodeId');
        var builderNodeProps = document.getElementById('builderNodeProps');
        var builderNodeStyle = document.getElementById('builderNodeStyle');
        var builderNodePropsHint = document.getElementById('builderNodePropsHint');
        var builderNodeStyleHint = document.getElementById('builderNodeStyleHint');
        var builderVisualConfig = document.getElementById('builderVisualConfig');
        var builderVisualHint = document.getElementById('builderVisualHint');
        var builderQuickPresetWrap = document.getElementById('builderQuickPresetWrap');
        var builderQuickPresetHint = document.getElementById('builderQuickPresetHint');
        var builderQuickPresetList = document.getElementById('builderQuickPresetList');
        var builderStylePresetWrap = document.getElementById('builderStylePresetWrap');
        var builderStylePresetHint = document.getElementById('builderStylePresetHint');
        var builderStylePresetList = document.getElementById('builderStylePresetList');
        var builderSectionVisualWrap = document.getElementById('builderSectionVisualWrap');
        var builderSectionContentWidth = document.getElementById('builderSectionContentWidth');
        var builderSectionInnerWidth = document.getElementById('builderSectionInnerWidth');
        var builderSectionBackgroundPicker = document.getElementById('builderSectionBackgroundPicker');
        var builderSectionBackground = document.getElementById('builderSectionBackground');
        var builderHeadingVisualWrap = document.getElementById('builderHeadingVisualWrap');
        var builderHeadingText = document.getElementById('builderHeadingText');
        var builderHeadingLevel = document.getElementById('builderHeadingLevel');
        var builderHeadingAlign = document.getElementById('builderHeadingAlign');
        var builderHeadingColorPicker = document.getElementById('builderHeadingColorPicker');
        var builderHeadingColor = document.getElementById('builderHeadingColor');
        var builderHeadingFontSize = document.getElementById('builderHeadingFontSize');
        var builderTextVisualWrap = document.getElementById('builderTextVisualWrap');
        var builderTextContent = document.getElementById('builderTextContent');
        var builderTextAlign = document.getElementById('builderTextAlign');
        var builderTextColorPicker = document.getElementById('builderTextColorPicker');
        var builderTextColor = document.getElementById('builderTextColor');
        var builderTextFontSize = document.getElementById('builderTextFontSize');
        var builderTextLineHeight = document.getElementById('builderTextLineHeight');
        var builderButtonVisualWrap = document.getElementById('builderButtonVisualWrap');
        var builderButtonText = document.getElementById('builderButtonText');
        var builderButtonHref = document.getElementById('builderButtonHref');
        var builderButtonTarget = document.getElementById('builderButtonTarget');
        var builderButtonAlign = document.getElementById('builderButtonAlign');
        var builderButtonVariant = document.getElementById('builderButtonVariant');
        var builderButtonBackgroundPicker = document.getElementById('builderButtonBackgroundPicker');
        var builderButtonBackground = document.getElementById('builderButtonBackground');
        var builderButtonColorPicker = document.getElementById('builderButtonColorPicker');
        var builderButtonColor = document.getElementById('builderButtonColor');
        var builderButtonRadius = document.getElementById('builderButtonRadius');
        var builderButtonMinHeight = document.getElementById('builderButtonMinHeight');
        var builderButtonBorderColor = document.getElementById('builderButtonBorderColor');
        var builderButtonPadding = document.getElementById('builderButtonPadding');
        var builderButtonHoverBackground = document.getElementById('builderButtonHoverBackground');
        var builderButtonHoverColor = document.getElementById('builderButtonHoverColor');
        var builderButtonHoverBorderColor = document.getElementById('builderButtonHoverBorderColor');
        var builderButtonHoverShadow = document.getElementById('builderButtonHoverShadow');
        var builderImageVisualWrap = document.getElementById('builderImageVisualWrap');
        var builderImageSourceType = document.getElementById('builderImageSourceType');
        var builderImageModelWrap = document.getElementById('builderImageModelWrap');
        var builderImageModel = document.getElementById('builderImageModel');
        var builderImageRecordIdWrap = document.getElementById('builderImageRecordIdWrap');
        var builderImageRecordId = document.getElementById('builderImageRecordId');
        var builderImageFieldWrap = document.getElementById('builderImageFieldWrap');
        var builderImageField = document.getElementById('builderImageField');
        var builderImageSrc = document.getElementById('builderImageSrc');
        var builderImageAlt = document.getElementById('builderImageAlt');
        var builderImageWidth = document.getElementById('builderImageWidth');
        var builderImageRadius = document.getElementById('builderImageRadius');
        var builderImageObjectFit = document.getElementById('builderImageObjectFit');
        var builderImageAlign = document.getElementById('builderImageAlign');
        var builderImageMinHeight = document.getElementById('builderImageMinHeight');
        var builderImageHoverScale = document.getElementById('builderImageHoverScale');
        var builderImageHoverShadow = document.getElementById('builderImageHoverShadow');
        var builderImagePreview = document.getElementById('builderImagePreview');
        var builderImageUploadTrigger = document.getElementById('builderImageUploadTrigger');
        var builderImageUploadInput = document.getElementById('builderImageUploadInput');
        var builderImageUploadHint = document.getElementById('builderImageUploadHint');
        var builderImageClear = document.getElementById('builderImageClear');
        var builderDividerVisualWrap = document.getElementById('builderDividerVisualWrap');
        var builderDividerColor = document.getElementById('builderDividerColor');
        var builderDividerThickness = document.getElementById('builderDividerThickness');
        var builderDividerStyle = document.getElementById('builderDividerStyle');
        var builderDividerWidth = document.getElementById('builderDividerWidth');
        var builderHtmlVisualWrap = document.getElementById('builderHtmlVisualWrap');
        var builderHtmlContent = document.getElementById('builderHtmlContent');
        var builderCarouselVisualWrap = document.getElementById('builderCarouselVisualWrap');
        var builderCarouselSourceType = document.getElementById('builderCarouselSourceType');
        var builderCarouselAutoplay = document.getElementById('builderCarouselAutoplay');
        var builderCarouselInterval = document.getElementById('builderCarouselInterval');
        var builderCarouselButtonText = document.getElementById('builderCarouselButtonText');
        var builderCarouselButtonHref = document.getElementById('builderCarouselButtonHref');
        var builderCarouselModelWrap = document.getElementById('builderCarouselModelWrap');
        var builderCarouselModel = document.getElementById('builderCarouselModel');
        var builderCarouselLimitWrap = document.getElementById('builderCarouselLimitWrap');
        var builderCarouselLimit = document.getElementById('builderCarouselLimit');
        var builderCarouselTitleFieldWrap = document.getElementById('builderCarouselTitleFieldWrap');
        var builderCarouselTitleField = document.getElementById('builderCarouselTitleField');
        var builderCarouselSummaryFieldWrap = document.getElementById('builderCarouselSummaryFieldWrap');
        var builderCarouselSummaryField = document.getElementById('builderCarouselSummaryField');
        var builderCarouselImageFieldWrap = document.getElementById('builderCarouselImageFieldWrap');
        var builderCarouselImageField = document.getElementById('builderCarouselImageField');
        var builderCarouselUrlFieldWrap = document.getElementById('builderCarouselUrlFieldWrap');
        var builderCarouselUrlField = document.getElementById('builderCarouselUrlField');
        var builderCarouselDetailPrefixWrap = document.getElementById('builderCarouselDetailPrefixWrap');
        var builderCarouselDetailPrefix = document.getElementById('builderCarouselDetailPrefix');
        var builderCarouselSlidesWrap = document.getElementById('builderCarouselSlidesWrap');
        var builderCarouselSlidesEditor = document.getElementById('builderCarouselSlidesEditor');
        var builderCarouselSlideAdd = document.getElementById('builderCarouselSlideAdd');
        var builderCarouselSlides = document.getElementById('builderCarouselSlides');
        var builderVideoVisualWrap = document.getElementById('builderVideoVisualWrap');
        var builderVideoSourceType = document.getElementById('builderVideoSourceType');
        var builderVideoTitle = document.getElementById('builderVideoTitle');
        var builderVideoEmbedUrlWrap = document.getElementById('builderVideoEmbedUrlWrap');
        var builderVideoEmbedUrl = document.getElementById('builderVideoEmbedUrl');
        var builderVideoMp4UrlWrap = document.getElementById('builderVideoMp4UrlWrap');
        var builderVideoMp4Url = document.getElementById('builderVideoMp4Url');
        var builderVideoPoster = document.getElementById('builderVideoPoster');
        var builderVideoAspectRatio = document.getElementById('builderVideoAspectRatio');
        var builderVideoControls = document.getElementById('builderVideoControls');
        var builderVideoAutoplay = document.getElementById('builderVideoAutoplay');
        var builderVideoMuted = document.getElementById('builderVideoMuted');
        var builderVideoLoop = document.getElementById('builderVideoLoop');
        var builderModelListVisualWrap = document.getElementById('builderModelListVisualWrap');
        var builderModelListPresetBar = document.getElementById('builderModelListPresetBar');
        var builderModelListTitle = document.getElementById('builderModelListTitle');
        var builderModelListTemplate = document.getElementById('builderModelListTemplate');
        var builderModelListModel = document.getElementById('builderModelListModel');
        var builderModelListLimit = document.getElementById('builderModelListLimit');
        var builderModelListOrderBy = document.getElementById('builderModelListOrderBy');
        var builderModelListOrderDirection = document.getElementById('builderModelListOrderDirection');
        var builderModelListTitleField = document.getElementById('builderModelListTitleField');
        var builderModelListSummaryField = document.getElementById('builderModelListSummaryField');
        var builderModelListImageField = document.getElementById('builderModelListImageField');
        var builderModelListDateField = document.getElementById('builderModelListDateField');
        var builderModelListUrlField = document.getElementById('builderModelListUrlField');
        var builderModelListDetailPrefix = document.getElementById('builderModelListDetailPrefix');
        var builderModelDetailVisualWrap = document.getElementById('builderModelDetailVisualWrap');
        var builderModelDetailPresetBar = document.getElementById('builderModelDetailPresetBar');
        var builderModelDetailTitle = document.getElementById('builderModelDetailTitle');
        var builderModelDetailTemplate = document.getElementById('builderModelDetailTemplate');
        var builderModelDetailModel = document.getElementById('builderModelDetailModel');
        var builderModelDetailRecordId = document.getElementById('builderModelDetailRecordId');
        var builderModelDetailTitleField = document.getElementById('builderModelDetailTitleField');
        var builderModelDetailSummaryField = document.getElementById('builderModelDetailSummaryField');
        var builderModelDetailContentField = document.getElementById('builderModelDetailContentField');
        var builderModelDetailImageField = document.getElementById('builderModelDetailImageField');
        var builderModelDetailDateField = document.getElementById('builderModelDetailDateField');
        var builderModelDetailUrlField = document.getElementById('builderModelDetailUrlField');
        var builderModelDetailDetailPrefix = document.getElementById('builderModelDetailDetailPrefix');
        var builderGalleryVisualWrap = document.getElementById('builderGalleryVisualWrap');
        var builderGalleryTitle = document.getElementById('builderGalleryTitle');
        var builderGallerySubtitle = document.getElementById('builderGallerySubtitle');
        var builderGallerySourceType = document.getElementById('builderGallerySourceType');
        var builderGalleryColumns = document.getElementById('builderGalleryColumns');
        var builderGalleryGap = document.getElementById('builderGalleryGap');
        var builderGalleryModelWrap = document.getElementById('builderGalleryModelWrap');
        var builderGalleryModel = document.getElementById('builderGalleryModel');
        var builderGalleryLimitWrap = document.getElementById('builderGalleryLimitWrap');
        var builderGalleryLimit = document.getElementById('builderGalleryLimit');
        var builderGalleryTitleFieldWrap = document.getElementById('builderGalleryTitleFieldWrap');
        var builderGalleryTitleField = document.getElementById('builderGalleryTitleField');
        var builderGalleryImageFieldWrap = document.getElementById('builderGalleryImageFieldWrap');
        var builderGalleryImageField = document.getElementById('builderGalleryImageField');
        var builderGalleryUrlFieldWrap = document.getElementById('builderGalleryUrlFieldWrap');
        var builderGalleryUrlField = document.getElementById('builderGalleryUrlField');
        var builderGalleryDetailPrefixWrap = document.getElementById('builderGalleryDetailPrefixWrap');
        var builderGalleryDetailPrefix = document.getElementById('builderGalleryDetailPrefix');
        var builderGalleryItemsWrap = document.getElementById('builderGalleryItemsWrap');
        var builderGalleryItemsEditor = document.getElementById('builderGalleryItemsEditor');
        var builderGalleryItemAdd = document.getElementById('builderGalleryItemAdd');
        var builderGalleryItems = document.getElementById('builderGalleryItems');
        var builderFaqVisualWrap = document.getElementById('builderFaqVisualWrap');
        var builderFaqTitle = document.getElementById('builderFaqTitle');
        var builderFaqIntro = document.getElementById('builderFaqIntro');
        var builderFaqColumns = document.getElementById('builderFaqColumns');
        var builderFaqItemsEditor = document.getElementById('builderFaqItemsEditor');
        var builderFaqItemAdd = document.getElementById('builderFaqItemAdd');
        var builderFaqItems = document.getElementById('builderFaqItems');
        var builderStatsVisualWrap = document.getElementById('builderStatsVisualWrap');
        var builderStatsTitle = document.getElementById('builderStatsTitle');
        var builderStatsIntro = document.getElementById('builderStatsIntro');
        var builderStatsColumns = document.getElementById('builderStatsColumns');
        var builderStatsItemsEditor = document.getElementById('builderStatsItemsEditor');
        var builderStatsItemAdd = document.getElementById('builderStatsItemAdd');
        var builderStatsItems = document.getElementById('builderStatsItems');
        var builderCtaVisualWrap = document.getElementById('builderCtaVisualWrap');
        var builderCtaEyebrow = document.getElementById('builderCtaEyebrow');
        var builderCtaTitle = document.getElementById('builderCtaTitle');
        var builderCtaDescription = document.getElementById('builderCtaDescription');
        var builderCtaPrimaryText = document.getElementById('builderCtaPrimaryText');
        var builderCtaPrimaryHref = document.getElementById('builderCtaPrimaryHref');
        var builderCtaSecondaryText = document.getElementById('builderCtaSecondaryText');
        var builderCtaSecondaryHref = document.getElementById('builderCtaSecondaryHref');
        var builderCtaAlign = document.getElementById('builderCtaAlign');
        var builderCtaActionsAlign = document.getElementById('builderCtaActionsAlign');
        var builderCtaPrimaryVariant = document.getElementById('builderCtaPrimaryVariant');
        var builderCtaSecondaryVariant = document.getElementById('builderCtaSecondaryVariant');
        var builderCtaButtonMinHeight = document.getElementById('builderCtaButtonMinHeight');
        var builderCtaHoverBackground = document.getElementById('builderCtaHoverBackground');
        var builderCtaHoverColor = document.getElementById('builderCtaHoverColor');
        var builderCtaHoverBorderColor = document.getElementById('builderCtaHoverBorderColor');
        var builderCtaHoverShadow = document.getElementById('builderCtaHoverShadow');
        var builderAnchorId = document.getElementById('builderAnchorId');
        var builderNavigationVisualWrap = document.getElementById('builderNavigationVisualWrap');
        var builderNavigationTitle = document.getElementById('builderNavigationTitle');
        var builderNavigationLogoType = document.getElementById('builderNavigationLogoType');
        var builderNavigationLayout = document.getElementById('builderNavigationLayout');
        var builderNavigationBrandHref = document.getElementById('builderNavigationBrandHref');
        var builderNavigationCtaText = document.getElementById('builderNavigationCtaText');
        var builderNavigationCtaHref = document.getElementById('builderNavigationCtaHref');
        var builderNavigationLogoImage = document.getElementById('builderNavigationLogoImage');
        var builderNavigationLogoSvg = document.getElementById('builderNavigationLogoSvg');
        var builderNavigationItemsEditor = document.getElementById('builderNavigationItemsEditor');
        var builderNavigationItemAdd = document.getElementById('builderNavigationItemAdd');
        var builderNavigationItems = document.getElementById('builderNavigationItems');
        var builderSidebarVisualWrap = document.getElementById('builderSidebarVisualWrap');
        var builderSidebarTitle = document.getElementById('builderSidebarTitle');
        var builderSidebarPosition = document.getElementById('builderSidebarPosition');
        var builderSidebarOffsetTop = document.getElementById('builderSidebarOffsetTop');
        var builderSidebarShowBackTop = document.getElementById('builderSidebarShowBackTop');
        var builderSidebarItemsEditor = document.getElementById('builderSidebarItemsEditor');
        var builderSidebarItemAdd = document.getElementById('builderSidebarItemAdd');
        var builderSidebarItems = document.getElementById('builderSidebarItems');
        var builderQrCodeVisualWrap = document.getElementById('builderQrCodeVisualWrap');
        var builderQrCodeTitle = document.getElementById('builderQrCodeTitle');
        var builderQrCodeText = document.getElementById('builderQrCodeText');
        var builderQrCodeValue = document.getElementById('builderQrCodeValue');
        var builderQrCodeSize = document.getElementById('builderQrCodeSize');
        var builderLoginVisualWrap = document.getElementById('builderLoginVisualWrap');
        var builderLoginTitle = document.getElementById('builderLoginTitle');
        var builderLoginAction = document.getElementById('builderLoginAction');
        var builderLoginButtonText = document.getElementById('builderLoginButtonText');
        var builderLoginProfileText = document.getElementById('builderLoginProfileText');
        var builderLoginProfileHref = document.getElementById('builderLoginProfileHref');
        var builderLoginAvatarUrl = document.getElementById('builderLoginAvatarUrl');
        var builderTabletStyle = document.getElementById('builderTabletStyle');
        var builderMobileStyle = document.getElementById('builderMobileStyle');
        var builderTabletPadding = document.getElementById('builderTabletPadding');
        var builderTabletMargin = document.getElementById('builderTabletMargin');
        var builderTabletFontSize = document.getElementById('builderTabletFontSize');
        var builderTabletGap = document.getElementById('builderTabletGap');
        var builderMobilePadding = document.getElementById('builderMobilePadding');
        var builderMobileMargin = document.getElementById('builderMobileMargin');
        var builderMobileFontSize = document.getElementById('builderMobileFontSize');
        var builderMobileGap = document.getElementById('builderMobileGap');
        var builderTabletSpanWrap = document.getElementById('builderTabletSpanWrap');
        var builderTabletSpan = document.getElementById('builderTabletSpan');
        var builderMobileSpanWrap = document.getElementById('builderMobileSpanWrap');
        var builderMobileSpan = document.getElementById('builderMobileSpan');
        var builderResponsiveHint = document.getElementById('builderResponsiveHint');
        var builderVisibilityConfig = document.getElementById('builderVisibilityConfig');
        var builderVisibilityEffect = document.getElementById('builderVisibilityEffect');
        var builderVisibilityRule = document.getElementById('builderVisibilityRule');
        var builderVisibilityLogic = document.getElementById('builderVisibilityLogic');
        var builderVisibilityExtraRule = document.getElementById('builderVisibilityExtraRule');
        var builderVisibilityParamWrap = document.getElementById('builderVisibilityParamWrap');
        var builderVisibilityParam = document.getElementById('builderVisibilityParam');
        var builderVisibilityValueWrap = document.getElementById('builderVisibilityValueWrap');
        var builderVisibilityValue = document.getElementById('builderVisibilityValue');
        var builderVisibilityDeviceWrap = document.getElementById('builderVisibilityDeviceWrap');
        var builderVisibilityDevices = document.getElementById('builderVisibilityDevices');
        var builderVisibilityExtraParamWrap = document.getElementById('builderVisibilityExtraParamWrap');
        var builderVisibilityExtraParam = document.getElementById('builderVisibilityExtraParam');
        var builderVisibilityExtraValueWrap = document.getElementById('builderVisibilityExtraValueWrap');
        var builderVisibilityExtraValue = document.getElementById('builderVisibilityExtraValue');
        var builderVisibilityExtraDeviceWrap = document.getElementById('builderVisibilityExtraDeviceWrap');
        var builderVisibilityExtraDevices = document.getElementById('builderVisibilityExtraDevices');
        var builderVisibilityHint = document.getElementById('builderVisibilityHint');
        var builderAnimationConfig = document.getElementById('builderAnimationConfig');
        var builderAnimationEffect = document.getElementById('builderAnimationEffect');
        var builderAnimationDuration = document.getElementById('builderAnimationDuration');
        var builderAnimationDelay = document.getElementById('builderAnimationDelay');
        var builderAnimationHint = document.getElementById('builderAnimationHint');
        var builderCommonStyleConfig = document.getElementById('builderCommonStyleConfig');
        var builderCommonWidth = document.getElementById('builderCommonWidth');
        var builderCommonMinHeight = document.getElementById('builderCommonMinHeight');
        var builderCommonRadius = document.getElementById('builderCommonRadius');
        var builderCommonBorderWidth = document.getElementById('builderCommonBorderWidth');
        var builderCommonBorderStyle = document.getElementById('builderCommonBorderStyle');
        var builderCommonBorderColor = document.getElementById('builderCommonBorderColor');
        var builderCommonBoxShadow = document.getElementById('builderCommonBoxShadow');
        var builderContainerConfig = document.getElementById('builderContainerConfig');
        var builderContainerClass = document.getElementById('builderContainerClass');
        var builderContainerBackground = document.getElementById('builderContainerBackground');
        var builderContainerPadding = document.getElementById('builderContainerPadding');
        var builderContainerMargin = document.getElementById('builderContainerMargin');
        var builderRowGapWrap = document.getElementById('builderRowGapWrap');
        var builderRowGap = document.getElementById('builderRowGap');
        var builderColumnSpanWrap = document.getElementById('builderColumnSpanWrap');
        var builderColumnSpan = document.getElementById('builderColumnSpan');
        var builderReusableName = document.getElementById('builderReusableName');
        var builderSaveReusable = document.getElementById('builderSaveReusable');
        var builderReusableHint = document.getElementById('builderReusableHint');
        var builderReusableList = document.getElementById('builderReusableList');
        var pageBuilderInspectorOutline = document.getElementById('pageBuilderInspectorOutline');
        var pageBuilderInspectorSummary = document.getElementById('pageBuilderInspectorSummary');
        var pageBuilderInspectorPanels = inspectorForm ? [].slice.call(inspectorForm.querySelectorAll('[data-inspector-panel]')) : [];
        var builderPaddingTop = document.getElementById('builderPaddingTop');
        var builderPaddingRight = document.getElementById('builderPaddingRight');
        var builderPaddingBottom = document.getElementById('builderPaddingBottom');
        var builderPaddingLeft = document.getElementById('builderPaddingLeft');
        var builderMarginTop = document.getElementById('builderMarginTop');
        var builderMarginRight = document.getElementById('builderMarginRight');
        var builderMarginBottom = document.getElementById('builderMarginBottom');
        var builderMarginLeft = document.getElementById('builderMarginLeft');
        if (!fillButton || !layoutSchema) {
            return;
        }

        var defaultSchema = {!! json_encode($formData['layout_schema'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!};
        var blockCatalog = {!! json_encode($blockCatalog, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!};
        var pageBuilderUploadUrl = @json(moduleAdminJump('formtools', "model?_token=" . csrf_token()));
        var pageBuilderModels = {!! json_encode($models->map(function ($model) {
            return [
                'id' => (string) $model->id,
                'identification' => (string) $model->identification,
                'name' => (string) $model->name,
            ];
        })->values(), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!};
        var builderPreviewAuthCheck = {!! json_encode(auth()->check(), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!};
        var builderPreviewAuthName = {!! json_encode(trim((string) (
            data_get(auth()->user(), 'nickname')
            ?: data_get(auth()->user(), 'name')
            ?: data_get(auth()->user(), 'username')
            ?: data_get(auth()->user(), 'realname')
            ?: data_get(auth()->user(), 'real_name')
            ?: ''
        )), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!};
        var builderPreviewAuthAvatar = {!! json_encode(trim((string) (
            data_get(auth()->user(), 'avatar')
            ?: data_get(auth()->user(), 'headimg')
            ?: data_get(auth()->user(), 'head_img')
            ?: data_get(auth()->user(), 'photo')
            ?: data_get(auth()->user(), 'image')
            ?: data_get(auth()->user(), 'thumb')
            ?: ''
        )), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!};
        var builderState = normalizeSchema(layoutSchema.value || defaultSchema);
        var selectedPath = '';
        var dragState = null;
        var previewDevice = 'desktop';
        var inspectorOutlineExpanded = false;
        var reusableStorageKey = 'mxzcms_page_builder_reusable_blocks';
        var catalogKeyword = '';
        var catalogGroup = 'layout';
        var catalogExpandedSections = {
            layout: true
        };
        var builderNoticeState = null;
        var activeInspectorTab = 'content';
        var collapsedSubpanelKeys = {};
        var quickInsertBlockTypes = ['heading', 'text', 'button', 'image', 'row', 'column', 'divider'];
        var pageFormDirty = false;
        var lastSavedSnapshot = '';

        function uniqueId(prefix) {
            return (prefix || 'node') + '_' + Date.now().toString(36) + Math.random().toString(36).slice(2, 7);
        }

        function deepClone(data) {
            return JSON.parse(JSON.stringify(data));
        }

        function escapeHtml(value) {
            return String(value || '')
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#39;');
        }

        function normalizeColorValue(value, fallback) {
            var color = String(value || '').trim();
            if (/^#([0-9a-fA-F]{6})$/.test(color)) {
                return color.toLowerCase();
            }
            if (/^#([0-9a-fA-F]{3})$/.test(color)) {
                return '#' + color.charAt(1) + color.charAt(1) + color.charAt(2) + color.charAt(2) + color.charAt(3) + color.charAt(3);
            }
            return fallback || '#000000';
        }

        function syncColorInput(textField, colorField, fallback) {
            if (!textField || !colorField) {
                return;
            }
            colorField.value = normalizeColorValue(textField.value, fallback);
        }

        function enhanceColorTextInput(textField, fallback) {
            if (!textField || textField.getAttribute('data-color-enhanced') === '1') {
                return null;
            }
            if (textField.closest('.ft-page-builder__color-input')) {
                textField.setAttribute('data-color-enhanced', '1');
                return null;
            }
            var parent = textField.parentNode;
            if (!parent) {
                return null;
            }
            var wrapper = document.createElement('div');
            wrapper.className = 'ft-page-builder__color-input';
            parent.insertBefore(wrapper, textField);
            var picker = document.createElement('input');
            picker.type = 'color';
            picker.setAttribute('data-color-enhanced-picker', textField.id || '');
            wrapper.appendChild(picker);
            wrapper.appendChild(textField);
            textField.setAttribute('data-color-enhanced', '1');
            syncColorInput(textField, picker, fallback || '#000000');
            picker.addEventListener('input', function () {
                textField.value = picker.value;
                applyVisualConfig();
            });
            textField.addEventListener('input', function () {
                syncColorInput(textField, picker, fallback || '#000000');
            });
            return picker;
        }

        function syncEnhancedColorTextInput(textField, fallback) {
            if (!textField) {
                return;
            }
            var picker = textField.parentNode && textField.parentNode.querySelector('[data-color-enhanced-picker="' + (textField.id || '') + '"]');
            if (picker) {
                syncColorInput(textField, picker, fallback || '#000000');
            }
        }

        function getListEditorFieldValue(item, key, fallback) {
            if (!item || typeof item !== 'object') {
                return fallback;
            }
            var value = item[key];
            if (value === undefined || value === null || value === '') {
                return fallback;
            }
            return value;
        }

        function locateSelectedNodeInPreview() {
            var node = getNodeByPath(selectedPath);
            if (!node || !node.id || !pageBuilderLivePreview) {
                return;
            }
            var target = pageBuilderLivePreview.querySelector('[data-node-id="' + String(node.id).replace(/"/g, '\\"') + '"]');
            if (!target) {
                return;
            }
            [].slice.call(pageBuilderLivePreview.querySelectorAll('[data-node-id].is-locating')).forEach(function (item) {
                item.classList.remove('is-locating');
            });
            target.classList.add('is-locating');
            target.scrollIntoView({behavior: 'smooth', block: 'center', inline: 'nearest'});
            window.setTimeout(function () {
                target.classList.remove('is-locating');
            }, 1800);
        }

        function locateSelectedNodeInEditor() {
            if (!selectedPath || !pageBuilderCanvas) {
                return;
            }
            var target = pageBuilderCanvas.querySelector('[data-path="' + String(selectedPath).replace(/"/g, '\\"') + '"]');
            if (!target) {
                return;
            }
            [].slice.call(pageBuilderCanvas.querySelectorAll('.ft-page-builder__node.is-locating')).forEach(function (item) {
                item.classList.remove('is-locating');
            });
            target.classList.add('is-locating');
            target.scrollIntoView({behavior: 'smooth', block: 'center', inline: 'nearest'});
            window.setTimeout(function () {
                target.classList.remove('is-locating');
            }, 1800);
        }

        function locateSelectedNodeEverywhere() {
            locateSelectedNodeInPreview();
            locateSelectedNodeInEditor();
        }

        function normalizeStringList(value) {
            if (Array.isArray(value)) {
                return value.map(function (item) {
                    return String(item || '').trim();
                }).filter(Boolean);
            }
            return String(value || '')
                .split(/[\s,|]+/)
                .map(function (item) {
                    return String(item || '').trim();
                })
                .filter(Boolean);
        }

        function serializeLinkItems(items, depth) {
            depth = depth || 0;
            return (items || []).map(function (item) {
                if (!item || typeof item !== 'object') {
                    return '';
                }
                var prefix = depth > 0 ? (new Array(depth + 1).join('- ')) : '';
                var line = prefix + String(item.text || '').trim() + '|' + String(item.href || '').trim();
                var children = Array.isArray(item.children) && item.children.length
                    ? '\n' + serializeLinkItems(item.children, depth + 1)
                    : '';
                return line + children;
            }).filter(Boolean).join('\n');
        }

        function serializeCarouselSlides(items) {
            return (items || []).map(function (item) {
                if (!item || typeof item !== 'object') {
                    return '';
                }
                return [
                    String(item.title || '').trim(),
                    String(item.description || '').trim(),
                    String(item.image || '').trim(),
                    String(item.buttonText || '').trim(),
                    String(item.buttonHref || '').trim()
                ].join('|');
            }).filter(Boolean).join('\n');
        }

        function parseCarouselSlides(text) {
            return String(text || '')
                .split(/\r?\n/)
                .map(function (line) {
                    return String(line || '').trim();
                })
                .filter(Boolean)
                .map(function (line) {
                    var parts = line.split('|');
                    return {
                        title: String(parts[0] || '').trim(),
                        description: String(parts[1] || '').trim(),
                        image: String(parts[2] || '').trim(),
                        buttonText: String(parts[3] || '').trim(),
                        buttonHref: String(parts[4] || '').trim()
                    };
                })
                .filter(function (item) {
                    return item.title || item.description || item.image;
                });
        }

        function serializeGalleryItems(items) {
            return (items || []).map(function (item) {
                if (!item || typeof item !== 'object') {
                    return '';
                }
                return [
                    String(item.title || '').trim(),
                    String(item.image || '').trim(),
                    String(item.url || '').trim()
                ].join('|');
            }).filter(Boolean).join('\n');
        }

        function parseGalleryItems(text) {
            return String(text || '')
                .split(/\r?\n/)
                .map(function (line) {
                    return String(line || '').trim();
                })
                .filter(Boolean)
                .map(function (line) {
                    var parts = line.split('|');
                    return {
                        title: String(parts[0] || '').trim(),
                        image: String(parts[1] || '').trim(),
                        url: String(parts[2] || '').trim()
                    };
                })
                .filter(function (item) {
                    return item.title || item.image || item.url;
                });
        }

        function serializeFaqItems(items) {
            return (items || []).map(function (item) {
                if (!item || typeof item !== 'object') {
                    return '';
                }
                return [
                    String(item.question || '').trim(),
                    String(item.answer || '').trim()
                ].join('|');
            }).filter(Boolean).join('\n');
        }

        function parseFaqItems(text) {
            return String(text || '')
                .split(/\r?\n/)
                .map(function (line) {
                    return String(line || '').trim();
                })
                .filter(Boolean)
                .map(function (line) {
                    var parts = line.split('|');
                    return {
                        question: String(parts[0] || '').trim(),
                        answer: parts.slice(1).join('|').trim()
                    };
                })
                .filter(function (item) {
                    return item.question || item.answer;
                });
        }

        function serializeStatsItems(items) {
            return (items || []).map(function (item) {
                if (!item || typeof item !== 'object') {
                    return '';
                }
                return [
                    String(item.label || '').trim(),
                    String(item.value || '').trim(),
                    String(item.suffix || '').trim(),
                    String(item.description || '').trim()
                ].join('|');
            }).filter(Boolean).join('\n');
        }

        function parseStatsItems(text) {
            return String(text || '')
                .split(/\r?\n/)
                .map(function (line) {
                    return String(line || '').trim();
                })
                .filter(Boolean)
                .map(function (line) {
                    var parts = line.split('|');
                    return {
                        label: String(parts[0] || '').trim(),
                        value: String(parts[1] || '').trim(),
                        suffix: String(parts[2] || '').trim(),
                        description: parts.slice(3).join('|').trim()
                    };
                })
                .filter(function (item) {
                    return item.label || item.value || item.description;
                });
        }

        function getNodeMotion(node) {
            return node && node.motion && typeof node.motion === 'object' ? node.motion : {};
        }

        function applyMotionConfig(node) {
            if (!node) {
                return;
            }
            var effect = builderAnimationEffect ? String(builderAnimationEffect.value || 'none').trim() : 'none';
            if (!effect || effect === 'none') {
                delete node.motion;
                if (builderAnimationHint) {
                    builderAnimationHint.textContent = '当前未启用入场动效。';
                    builderAnimationHint.classList.remove('ft-page-builder__field-error');
                }
                return;
            }
            node.motion = {
                effect: effect,
                duration: builderAnimationDuration ? String(builderAnimationDuration.value || '').trim() : '',
                delay: builderAnimationDelay ? String(builderAnimationDelay.value || '').trim() : ''
            };
            if (builderAnimationHint) {
                builderAnimationHint.textContent = '当前已启用 `' + effect + '` 动效，适合首屏、图片、轮播和按钮组件。';
                builderAnimationHint.classList.remove('ft-page-builder__field-error');
            }
        }

        function syncImageSourceFields() {
            var isDynamic = builderImageSourceType && builderImageSourceType.value === 'model_detail';
            toggleElement(builderImageModelWrap, !!isDynamic, 'block');
            toggleElement(builderImageRecordIdWrap, !!isDynamic, 'block');
            toggleElement(builderImageFieldWrap, !!isDynamic, 'block');
        }

        function syncCarouselSourceFields() {
            var isModel = builderCarouselSourceType && builderCarouselSourceType.value === 'model_list';
            toggleElement(builderCarouselModelWrap, !!isModel, 'block');
            toggleElement(builderCarouselLimitWrap, !!isModel, 'block');
            toggleElement(builderCarouselTitleFieldWrap, !!isModel, 'block');
            toggleElement(builderCarouselSummaryFieldWrap, !!isModel, 'block');
            toggleElement(builderCarouselImageFieldWrap, !!isModel, 'block');
            toggleElement(builderCarouselUrlFieldWrap, !!isModel, 'block');
            toggleElement(builderCarouselDetailPrefixWrap, !!isModel, 'block');
            toggleElement(builderCarouselSlidesWrap, !isModel, 'block');
        }

        function syncVideoSourceFields() {
            var isMp4 = builderVideoSourceType && builderVideoSourceType.value === 'mp4';
            toggleElement(builderVideoEmbedUrlWrap, !isMp4, 'block');
            toggleElement(builderVideoMp4UrlWrap, !!isMp4, 'block');
        }

        function syncGallerySourceFields() {
            var isModel = builderGallerySourceType && builderGallerySourceType.value === 'model_list';
            toggleElement(builderGalleryModelWrap, !!isModel, 'block');
            toggleElement(builderGalleryLimitWrap, !!isModel, 'block');
            toggleElement(builderGalleryTitleFieldWrap, !!isModel, 'block');
            toggleElement(builderGalleryImageFieldWrap, !!isModel, 'block');
            toggleElement(builderGalleryUrlFieldWrap, !!isModel, 'block');
            toggleElement(builderGalleryDetailPrefixWrap, !!isModel, 'block');
            toggleElement(builderGalleryItemsWrap, !isModel, 'block');
        }

        function parseLinkItems(text) {
            var roots = [];
            var stack = [];
            String(text || '').split(/\r?\n/).forEach(function (line) {
                var raw = String(line || '');
                if (!raw.trim()) {
                    return;
                }
                var match = raw.match(/^(\s*-\s*)+/);
                var depth = 0;
                if (match) {
                    depth = (match[0].match(/-/g) || []).length;
                }
                var row = raw.replace(/^(\s*-\s*)+/, '').trim();
                var parts = row.split('|');
                var label = String(parts[0] || '').trim();
                var href = String(parts.slice(1).join('|') || '').trim() || '#';
                if (!label) {
                    return;
                }
                var item = {
                    text: label,
                    href: href
                };
                if (depth <= 0) {
                    roots.push(item);
                    stack = [item];
                    return;
                }
                var parent = stack[Math.max(0, depth - 1)];
                if (!parent) {
                    roots.push(item);
                    stack = [item];
                    return;
                }
                parent.children = Array.isArray(parent.children) ? parent.children : [];
                parent.children.push(item);
                stack = stack.slice(0, depth);
                stack[depth] = item;
            });
            return roots;
        }

        function serializeSidebarItems(items) {
            return (items || []).map(function (item) {
                if (!item || typeof item !== 'object') {
                    return '';
                }
                return [
                    String(item.text || '').trim(),
                    String(item.href || '').trim(),
                    String(item.icon || '').trim(),
                    String(item.actionType || '').trim(),
                    String(item.panelType || '').trim(),
                    String(item.panelTitle || '').trim(),
                    String(item.panelContent || '').trim(),
                    String(item.panelValue || '').trim(),
                    String(item.panelHtml || '').trim(),
                    String(item.background || '').trim(),
                    String(item.color || '').trim(),
                    String(item.borderColor || '').trim()
                ].join('|');
            }).filter(Boolean).join('\n');
        }

        function parseSidebarItems(text) {
            return String(text || '').split(/\r?\n/).map(function (line) {
                var raw = String(line || '').trim();
                if (!raw) {
                    return null;
                }
                var parts = raw.split('|');
                var text = String(parts[0] || '').trim();
                var href = String(parts[1] || '').trim() || '#';
                var icon = String(parts[2] || '').trim();
                var actionType = String(parts[3] || '').trim() || 'link';
                var panelType = String(parts[4] || '').trim() || 'qrcode';
                var panelTitle = String(parts[5] || '').trim();
                var panelContent = String(parts[6] || '').trim();
                var panelValue = String(parts[7] || '').trim();
                var panelHtml = String(parts[8] || '').trim();
                var background = String(parts[9] || '').trim();
                var color = String(parts[10] || '').trim();
                var borderColor = String(parts[11] || '').trim();
                if (!text) {
                    return null;
                }
                return {
                    text: text,
                    href: href,
                    icon: icon,
                    actionType: actionType === 'panel' ? 'panel' : 'link',
                    panelType: panelType === 'custom' ? 'custom' : 'qrcode',
                    panelTitle: panelTitle,
                    panelContent: panelContent,
                    panelValue: panelValue,
                    panelHtml: panelHtml,
                    background: background,
                    color: color,
                    borderColor: borderColor
                };
            }).filter(Boolean);
        }

        function getSimpleListEditorConfigs() {
            return {
                carousel: {
                    container: builderCarouselSlidesEditor,
                    textarea: builderCarouselSlides,
                    title: '轮播项',
                    createItem: function () {
                        return {title: '', description: '', image: '', buttonText: '', buttonHref: ''};
                    },
                    fields: [
                        {key: 'title', label: '标题', placeholder: '这里是一张轮播主视觉'},
                        {key: 'description', label: '描述', placeholder: '这里放轮播描述', full: true, tag: 'textarea'},
                        {key: 'image', label: '图片地址', placeholder: 'https://example.com/slide.jpg', full: true},
                        {key: 'buttonText', label: '按钮文案', placeholder: '立即咨询'},
                        {key: 'buttonHref', label: '按钮链接', placeholder: '/contact'}
                    ],
                    serialize: serializeCarouselSlides
                },
                gallery: {
                    container: builderGalleryItemsEditor,
                    textarea: builderGalleryItems,
                    title: '图片项',
                    createItem: function () {
                        return {title: '', image: '', url: ''};
                    },
                    fields: [
                        {key: 'title', label: '标题', placeholder: '案例一'},
                        {key: 'url', label: '链接', placeholder: '/cases/1'},
                        {key: 'image', label: '图片地址', placeholder: 'https://example.com/case.jpg', full: true}
                    ],
                    serialize: serializeGalleryItems
                },
                faq: {
                    container: builderFaqItemsEditor,
                    textarea: builderFaqItems,
                    title: '问题项',
                    createItem: function () {
                        return {question: '', answer: ''};
                    },
                    fields: [
                        {key: 'question', label: '问题', placeholder: '多久可以上线？'},
                        {key: 'answer', label: '答案', placeholder: '这里填写答案说明', full: true, tag: 'textarea'}
                    ],
                    serialize: serializeFaqItems
                },
                stats: {
                    container: builderStatsItemsEditor,
                    textarea: builderStatsItems,
                    title: '指标项',
                    createItem: function () {
                        return {label: '', value: '', suffix: '', description: ''};
                    },
                    fields: [
                        {key: 'label', label: '标签', placeholder: '服务客户'},
                        {key: 'value', label: '数值', placeholder: '2580'},
                        {key: 'suffix', label: '后缀', placeholder: '+'},
                        {key: 'description', label: '说明', placeholder: '覆盖多行业项目', full: true}
                    ],
                    serialize: serializeStatsItems
                },
                sidebar: {
                    container: builderSidebarItemsEditor,
                    textarea: builderSidebarItems,
                    title: '侧栏项目',
                    createItem: function () {
                        return {text: '', href: '', icon: '', actionType: 'link', panelType: 'qrcode', panelTitle: '', panelContent: '', panelValue: '', panelHtml: '', background: '', color: '', borderColor: ''};
                    },
                    fields: [
                        {key: 'text', label: '标题', placeholder: '在线咨询'},
                        {key: 'actionType', label: '操作', type: 'select', refresh: true, options: [{value: 'link', label: '跳转链接'}, {value: 'panel', label: '展示面板'}]},
                        {key: 'href', label: '链接', placeholder: '#contact / tel:400-800-1234', showWhen: function (item) { return String(getListEditorFieldValue(item, 'actionType', 'link')) !== 'panel'; }},
                        {key: 'icon', label: '图标源', placeholder: '咨 / /uploads/kefu.png / <svg ...>', full: true, tag: 'textarea'},
                        {key: 'panelType', label: '面板类型', type: 'select', refresh: true, options: [{value: 'qrcode', label: '二维码'}, {value: 'custom', label: '自定义内容'}], showWhen: function (item) { return String(getListEditorFieldValue(item, 'actionType', 'link')) === 'panel'; }},
                        {key: 'panelTitle', label: '面板标题', placeholder: '扫码咨询', showWhen: function (item) { return String(getListEditorFieldValue(item, 'actionType', 'link')) === 'panel'; }},
                        {key: 'panelValue', label: '二维码值/扩展值', placeholder: 'https://example.com/wechat', full: true, showWhen: function (item) { return String(getListEditorFieldValue(item, 'actionType', 'link')) === 'panel'; }},
                        {key: 'panelContent', label: '面板内容', placeholder: '添加顾问获取方案与报价', full: true, tag: 'textarea', showWhen: function (item) { return String(getListEditorFieldValue(item, 'actionType', 'link')) === 'panel'; }},
                        {key: 'panelHtml', label: 'HTML 内容', placeholder: '<div><h4>活动资料</h4><p>这里可放富文本、自定义 HTML。</p></div>', full: true, tag: 'textarea', showWhen: function (item) { return String(getListEditorFieldValue(item, 'actionType', 'link')) === 'panel' && String(getListEditorFieldValue(item, 'panelType', 'qrcode')) === 'custom'; }},
                        {key: 'background', label: '背景色', placeholder: '#ffffff', type: 'color'},
                        {key: 'color', label: '文字色', placeholder: '#2563eb', type: 'color'},
                        {key: 'borderColor', label: '边框色', placeholder: '#bfdbfe', type: 'color'}
                    ],
                    serialize: serializeSidebarItems
                }
            };
        }

        function renderSimpleListEditor(type, items) {
            var config = getSimpleListEditorConfigs()[type];
            if (!config || !config.container) {
                return;
            }
            var list = Array.isArray(items) ? items : [];
            if (!list.length) {
                config.container.innerHTML = '<div class="ft-page-builder__list-empty">当前还没有' + escapeHtml(config.title) + '，可以直接新增。</div>';
                return;
            }
            config.container.innerHTML = list.map(function (item, index) {
                var isFirst = index === 0;
                var isLast = index === list.length - 1;
                return '<div class="ft-page-builder__list-card" data-list-editor="' + escapeHtml(type) + '" data-list-item-index="' + index + '">' +
                    '<div class="ft-page-builder__list-card-head">' +
                        '<span class="ft-page-builder__list-card-title">' + escapeHtml(config.title) + ' ' + (index + 1) + '</span>' +
                        '<div class="ft-page-builder__list-card-head-actions">' +
                            '<button type="button" class="btn btn-default btn-xs" data-list-move="' + escapeHtml(type) + '" data-list-index="' + index + '" data-list-direction="up"' + (isFirst ? ' disabled' : '') + '>上移</button>' +
                            '<button type="button" class="btn btn-default btn-xs" data-list-move="' + escapeHtml(type) + '" data-list-index="' + index + '" data-list-direction="down"' + (isLast ? ' disabled' : '') + '>下移</button>' +
                            '<button type="button" class="btn btn-default btn-xs" data-list-duplicate="' + escapeHtml(type) + '" data-list-index="' + index + '">复制</button>' +
                            '<button type="button" class="btn btn-default btn-xs" data-list-remove="' + escapeHtml(type) + '" data-list-index="' + index + '">删除</button>' +
                        '</div>' +
                    '</div>' +
                    '<div class="ft-page-builder__list-card-grid">' + config.fields.map(function (field) {
                        if (typeof field.showWhen === 'function' && !field.showWhen(item || {})) {
                            return '';
                        }
                        var value = item && item[field.key] ? String(item[field.key]) : '';
                        var tag = field.tag === 'textarea' ? 'textarea' : 'input';
                        var fieldId = 'builder-list-' + type + '-' + index + '-' + field.key;
                        var inputHtml = field.type === 'select'
                            ? '<select id="' + escapeHtml(fieldId) + '" data-list-field="' + escapeHtml(field.key) + '"' + (field.refresh ? ' data-list-refresh="1"' : '') + '>' + (field.options || []).map(function (option) {
                                var optionValue = option && option.value ? String(option.value) : '';
                                var optionLabel = option && option.label ? String(option.label) : optionValue;
                                return '<option value="' + escapeHtml(optionValue) + '"' + (String(value) === optionValue ? ' selected' : '') + '>' + escapeHtml(optionLabel) + '</option>';
                            }).join('') + '</select>'
                            : field.type === 'color'
                            ? '<div class="ft-page-builder__color-input"><input id="' + escapeHtml(fieldId) + '-picker" type="color" data-list-color-picker="' + escapeHtml(field.key) + '" value="' + escapeHtml(normalizeColorValue(value, field.placeholder || '#000000')) + '"><input id="' + escapeHtml(fieldId) + '" type="text" data-list-field="' + escapeHtml(field.key) + '" value="' + escapeHtml(value) + '" placeholder="' + escapeHtml(field.placeholder || '') + '"></div>'
                            : tag === 'textarea'
                            ? '<textarea id="' + escapeHtml(fieldId) + '" data-list-field="' + escapeHtml(field.key) + '" placeholder="' + escapeHtml(field.placeholder || '') + '">' + escapeHtml(value) + '</textarea>'
                            : '<input id="' + escapeHtml(fieldId) + '" type="text" data-list-field="' + escapeHtml(field.key) + '" value="' + escapeHtml(value) + '" placeholder="' + escapeHtml(field.placeholder || '') + '">';
                        return '<div class="ft-page-builder__field' + (field.full ? ' ft-page-form__full' : '') + '">' +
                            '<label for="' + escapeHtml(fieldId) + '">' + escapeHtml(field.label) + '</label>' +
                            inputHtml +
                        '</div>';
                    }).join('') + '</div>' +
                '</div>';
            }).join('');
        }

        function readSimpleListEditorItems(type, includeEmpty) {
            var config = getSimpleListEditorConfigs()[type];
            if (!config || !config.container) {
                return [];
            }
            var items = [].slice.call(config.container.querySelectorAll('[data-list-item-index]')).map(function (card) {
                var item = {};
                config.fields.forEach(function (field) {
                    var input = card.querySelector('[data-list-field="' + field.key + '"]');
                    item[field.key] = input ? String(input.value || '').trim() : '';
                });
                return item;
            });
            if (includeEmpty) {
                return items;
            }
            return items.filter(function (item) {
                return Object.keys(item).some(function (key) {
                    return String(item[key] || '').trim() !== '';
                });
            });
        }

        function syncSimpleListEditor(type, shouldApply) {
            var config = getSimpleListEditorConfigs()[type];
            if (!config || !config.textarea) {
                return;
            }
            config.textarea.value = config.serialize(readSimpleListEditorItems(type, false));
            if (shouldApply !== false) {
                applyVisualConfig();
            }
        }

        function updateSimpleListEditor(type, updater, shouldApply) {
            var config = getSimpleListEditorConfigs()[type];
            if (!config) {
                return;
            }
            var items = readSimpleListEditorItems(type, true);
            updater(items, config);
            renderSimpleListEditor(type, items);
            syncSimpleListEditor(type, shouldApply !== false);
        }

        function moveArrayItem(items, fromIndex, toIndex) {
            if (!Array.isArray(items)) {
                return;
            }
            if (fromIndex < 0 || fromIndex >= items.length || toIndex < 0 || toIndex >= items.length || fromIndex === toIndex) {
                return;
            }
            var moved = items.splice(fromIndex, 1)[0];
            items.splice(toIndex, 0, moved);
        }

        function clonePlainItem(item) {
            return item && typeof item === 'object' ? JSON.parse(JSON.stringify(item)) : {};
        }

        function getModelFieldPresetMap() {
            return {
                'model_list:article': {
                    title: '文章列表',
                    template: 'list',
                    limit: '8',
                    order_by: 'created_at',
                    order_direction: 'desc',
                    title_field: 'title',
                    summary_field: 'summary',
                    image_field: 'cover',
                    date_field: 'created_at',
                    url_field: 'url',
                    detail_prefix: '/article'
                },
                'model_list:product': {
                    title: '产品列表',
                    template: 'card',
                    limit: '6',
                    order_by: 'sort',
                    order_direction: 'asc',
                    title_field: 'title',
                    summary_field: 'description',
                    image_field: 'image',
                    date_field: '',
                    url_field: 'url',
                    detail_prefix: '/product'
                },
                'model_list:news': {
                    title: '资讯列表',
                    template: 'card',
                    limit: '6',
                    order_by: 'publish_at',
                    order_direction: 'desc',
                    title_field: 'title',
                    summary_field: 'summary',
                    image_field: 'cover',
                    date_field: 'publish_at',
                    url_field: 'url',
                    detail_prefix: '/news'
                },
                'model_detail:article': {
                    title: '文章详情',
                    template: 'article',
                    title_field: 'title',
                    summary_field: 'summary',
                    content_field: 'content',
                    image_field: 'cover',
                    date_field: 'created_at',
                    url_field: 'url',
                    detail_prefix: '/article'
                },
                'model_detail:product': {
                    title: '产品详情',
                    template: 'detail',
                    title_field: 'title',
                    summary_field: 'subtitle',
                    content_field: 'description',
                    image_field: 'image',
                    date_field: 'updated_at',
                    url_field: 'url',
                    detail_prefix: '/product'
                },
                'model_detail:profile': {
                    title: '介绍页',
                    template: 'detail',
                    title_field: 'title',
                    summary_field: 'summary',
                    content_field: 'content',
                    image_field: 'cover',
                    date_field: '',
                    url_field: 'url',
                    detail_prefix: '/about'
                }
            };
        }

        function applyModelFieldPreset(presetKey) {
            var preset = getModelFieldPresetMap()[presetKey];
            if (!preset) {
                return;
            }
            if (presetKey.indexOf('model_list:') === 0) {
                if (builderModelListTitle) builderModelListTitle.value = preset.title || '';
                if (builderModelListTemplate) builderModelListTemplate.value = preset.template || 'card';
                if (builderModelListLimit) builderModelListLimit.value = preset.limit || '';
                if (builderModelListOrderBy) builderModelListOrderBy.value = preset.order_by || '';
                if (builderModelListOrderDirection) builderModelListOrderDirection.value = preset.order_direction || 'desc';
                if (builderModelListTitleField) builderModelListTitleField.value = preset.title_field || '';
                if (builderModelListSummaryField) builderModelListSummaryField.value = preset.summary_field || '';
                if (builderModelListImageField) builderModelListImageField.value = preset.image_field || '';
                if (builderModelListDateField) builderModelListDateField.value = preset.date_field || '';
                if (builderModelListUrlField) builderModelListUrlField.value = preset.url_field || '';
                if (builderModelListDetailPrefix) builderModelListDetailPrefix.value = preset.detail_prefix || '';
            } else if (presetKey.indexOf('model_detail:') === 0) {
                if (builderModelDetailTitle) builderModelDetailTitle.value = preset.title || '';
                if (builderModelDetailTemplate) builderModelDetailTemplate.value = preset.template || 'detail';
                if (builderModelDetailTitleField) builderModelDetailTitleField.value = preset.title_field || '';
                if (builderModelDetailSummaryField) builderModelDetailSummaryField.value = preset.summary_field || '';
                if (builderModelDetailContentField) builderModelDetailContentField.value = preset.content_field || '';
                if (builderModelDetailImageField) builderModelDetailImageField.value = preset.image_field || '';
                if (builderModelDetailDateField) builderModelDetailDateField.value = preset.date_field || '';
                if (builderModelDetailUrlField) builderModelDetailUrlField.value = preset.url_field || '';
                if (builderModelDetailDetailPrefix) builderModelDetailDetailPrefix.value = preset.detail_prefix || '';
            }
            applyVisualConfig();
        }

        function normalizeNavigationItemsForEditor(items) {
            return (items || []).map(function (item) {
                if (!item || typeof item !== 'object') {
                    return null;
                }
                return {
                    text: String(item.text || '').trim(),
                    href: String(item.href || '').trim(),
                    children: (Array.isArray(item.children) ? item.children : []).map(function (child) {
                        if (!child || typeof child !== 'object') {
                            return null;
                        }
                        return {
                            text: String(child.text || '').trim(),
                            href: String(child.href || '').trim()
                        };
                    }).filter(Boolean)
                };
            }).filter(Boolean);
        }

        function renderNavigationItemsEditor(items) {
            if (!builderNavigationItemsEditor) {
                return;
            }
            var list = normalizeNavigationItemsForEditor(items);
            if (!list.length) {
                builderNavigationItemsEditor.innerHTML = '<div class="ft-page-builder__list-empty">当前还没有导航项，可以直接新增一级菜单。</div>';
                return;
            }
            builderNavigationItemsEditor.innerHTML = list.map(function (item, index) {
                var children = Array.isArray(item.children) ? item.children : [];
                var isFirstRoot = index === 0;
                var isLastRoot = index === list.length - 1;
                var rootTextId = 'builder-nav-root-text-' + index;
                var rootHrefId = 'builder-nav-root-href-' + index;
                return '<div class="ft-page-builder__nav-card" data-nav-root-index="' + index + '">' +
                    '<div class="ft-page-builder__nav-card-head">' +
                        '<span class="ft-page-builder__nav-card-title">一级菜单 ' + (index + 1) + '</span>' +
                        '<div class="ft-page-builder__nav-card-head-actions">' +
                            '<button type="button" class="btn btn-default btn-xs" data-nav-move-root="' + index + '" data-nav-direction="up"' + (isFirstRoot ? ' disabled' : '') + '>上移</button>' +
                            '<button type="button" class="btn btn-default btn-xs" data-nav-move-root="' + index + '" data-nav-direction="down"' + (isLastRoot ? ' disabled' : '') + '>下移</button>' +
                            '<button type="button" class="btn btn-default btn-xs" data-nav-duplicate-root="' + index + '">复制</button>' +
                            '<button type="button" class="btn btn-default btn-xs" data-nav-remove-root="' + index + '">删除</button>' +
                        '</div>' +
                    '</div>' +
                    '<div class="ft-page-builder__nav-card-grid">' +
                        '<div class="ft-page-builder__field"><label for="' + escapeHtml(rootTextId) + '">菜单文字</label><input id="' + escapeHtml(rootTextId) + '" type="text" data-nav-field="text" value="' + escapeHtml(item.text || '') + '" placeholder="首页"></div>' +
                        '<div class="ft-page-builder__field"><label for="' + escapeHtml(rootHrefId) + '">菜单链接</label><input id="' + escapeHtml(rootHrefId) + '" type="text" data-nav-field="href" value="' + escapeHtml(item.href || '') + '" placeholder="/"></div>' +
                    '</div>' +
                    '<div class="ft-page-builder__nav-children">' +
                        '<div class="ft-page-builder__nav-children-head">' +
                            '<span class="ft-page-builder__nav-children-title">二级菜单</span>' +
                            '<button type="button" class="btn btn-default btn-xs" data-nav-add-child="' + index + '">新增二级菜单</button>' +
                        '</div>' +
                        (children.length ? children.map(function (child, childIndex) {
                            var isFirstChild = childIndex === 0;
                            var isLastChild = childIndex === children.length - 1;
                            var childTextId = 'builder-nav-child-text-' + index + '-' + childIndex;
                            var childHrefId = 'builder-nav-child-href-' + index + '-' + childIndex;
                            return '<div class="ft-page-builder__nav-child" data-nav-child-index="' + childIndex + '">' +
                                '<div class="ft-page-builder__nav-card-head">' +
                                    '<span class="ft-page-builder__nav-card-title">子菜单 ' + (childIndex + 1) + '</span>' +
                                    '<div class="ft-page-builder__nav-card-head-actions">' +
                                        '<button type="button" class="btn btn-default btn-xs" data-nav-move-child="' + index + '" data-nav-child="' + childIndex + '" data-nav-direction="up"' + (isFirstChild ? ' disabled' : '') + '>上移</button>' +
                                        '<button type="button" class="btn btn-default btn-xs" data-nav-move-child="' + index + '" data-nav-child="' + childIndex + '" data-nav-direction="down"' + (isLastChild ? ' disabled' : '') + '>下移</button>' +
                                        '<button type="button" class="btn btn-default btn-xs" data-nav-duplicate-child="' + index + '" data-nav-child="' + childIndex + '">复制</button>' +
                                        '<button type="button" class="btn btn-default btn-xs" data-nav-remove-child="' + index + '" data-nav-child="' + childIndex + '">删除</button>' +
                                    '</div>' +
                                '</div>' +
                                '<div class="ft-page-builder__nav-child-grid">' +
                                    '<div class="ft-page-builder__field"><label for="' + escapeHtml(childTextId) + '">菜单文字</label><input id="' + escapeHtml(childTextId) + '" type="text" data-nav-child-field="text" value="' + escapeHtml(child.text || '') + '" placeholder="产品总览"></div>' +
                                    '<div class="ft-page-builder__field"><label for="' + escapeHtml(childHrefId) + '">菜单链接</label><input id="' + escapeHtml(childHrefId) + '" type="text" data-nav-child-field="href" value="' + escapeHtml(child.href || '') + '" placeholder="/products"></div>' +
                                '</div>' +
                            '</div>';
                        }).join('') : '<div class="ft-page-builder__list-empty">当前没有二级菜单。</div>') +
                    '</div>' +
                '</div>';
            }).join('');
        }

        function readNavigationItemsEditor(includeEmpty) {
            if (!builderNavigationItemsEditor) {
                return [];
            }
            var items = [].slice.call(builderNavigationItemsEditor.querySelectorAll('[data-nav-root-index]')).map(function (card) {
                var item = {
                    text: '',
                    href: '#'
                };
                var textInput = card.querySelector('[data-nav-field="text"]');
                var hrefInput = card.querySelector('[data-nav-field="href"]');
                item.text = textInput ? String(textInput.value || '').trim() : '';
                item.href = hrefInput ? String(hrefInput.value || '').trim() || '#' : '#';
                var children = [].slice.call(card.querySelectorAll('[data-nav-child-index]')).map(function (childCard) {
                    var childTextInput = childCard.querySelector('[data-nav-child-field="text"]');
                    var childHrefInput = childCard.querySelector('[data-nav-child-field="href"]');
                    return {
                        text: childTextInput ? String(childTextInput.value || '').trim() : '',
                        href: childHrefInput ? String(childHrefInput.value || '').trim() || '#' : '#'
                    };
                });
                if (!includeEmpty) {
                    children = children.filter(function (child) {
                        return child.text || child.href !== '#';
                    });
                }
                if (children.length) {
                    item.children = children;
                }
                return item;
            });
            if (includeEmpty) {
                return items;
            }
            return items.filter(function (item) {
                return item.text || item.href !== '#' || (Array.isArray(item.children) && item.children.length);
            });
        }

        function syncNavigationItemsEditor(shouldApply) {
            if (!builderNavigationItems) {
                return;
            }
            builderNavigationItems.value = serializeLinkItems(readNavigationItemsEditor(false));
            if (shouldApply !== false) {
                applyVisualConfig();
            }
        }

        function updateNavigationItemsEditor(updater, shouldApply) {
            var items = readNavigationItemsEditor(true);
            updater(items);
            renderNavigationItemsEditor(items);
            syncNavigationItemsEditor(shouldApply !== false);
        }

        function getNodeVisibility(node) {
            return node && node.visibility && typeof node.visibility === 'object' ? node.visibility : {};
        }

        function getVisibilityRuleLabel(rule) {
            var labels = {
                logged_in: '已登录',
                guest: '未登录',
                url_param: 'URL 参数',
                device: '设备'
            };
            return labels[String(rule || '').trim()] || '条件';
        }

        function getVisibilitySummary(node) {
            var visibility = getNodeVisibility(node);
            var effect = String(visibility.effect || 'always').trim();
            if (!effect || effect === 'always') {
                return '';
            }
            var summary = (effect === 'show' ? '显示' : '隐藏') + ' · ' + getVisibilityRuleLabel(visibility.rule);
            if (visibility.extraRule) {
                summary += ' ' + (String(visibility.logic || 'all') === 'any' ? '或' : '且') + ' ' + getVisibilityRuleLabel(visibility.extraRule);
            }
            return summary;
        }

        function syncVisibilityRuleFields() {
            var effect = builderVisibilityEffect ? builderVisibilityEffect.value : 'always';
            var rule = builderVisibilityRule ? builderVisibilityRule.value : 'logged_in';
            var extraRule = builderVisibilityExtraRule ? builderVisibilityExtraRule.value : '';
            toggleElement(builderVisibilityParamWrap, effect !== 'always' && rule === 'url_param');
            toggleElement(builderVisibilityValueWrap, effect !== 'always' && rule === 'url_param');
            toggleElement(builderVisibilityDeviceWrap, effect !== 'always' && rule === 'device');
            toggleElement(builderVisibilityExtraParamWrap, effect !== 'always' && extraRule === 'url_param');
            toggleElement(builderVisibilityExtraValueWrap, effect !== 'always' && extraRule === 'url_param');
            toggleElement(builderVisibilityExtraDeviceWrap, effect !== 'always' && extraRule === 'device');
        }

        function applyVisibilityConfig(node) {
            var targetNode = node || getNodeByPath(selectedPath);
            if (!targetNode || !builderVisibilityEffect || !builderVisibilityRule) {
                return;
            }
            var effect = builderVisibilityEffect.value || 'always';
            if (effect === 'always') {
                delete targetNode.visibility;
                if (builderVisibilityHint) {
                    builderVisibilityHint.classList.remove('ft-page-builder__field-error');
                    builderVisibilityHint.textContent = '默认始终显示。条件命中后，可选择“显示”或“隐藏”当前区块。';
                }
                return;
            }
            targetNode.visibility = targetNode.visibility && typeof targetNode.visibility === 'object' ? targetNode.visibility : {};
            targetNode.visibility.effect = effect;
            targetNode.visibility.logic = builderVisibilityLogic ? (builderVisibilityLogic.value || 'all') : 'all';
            targetNode.visibility.rule = builderVisibilityRule.value || 'logged_in';
            if (targetNode.visibility.rule === 'url_param') {
                targetNode.visibility.param = builderVisibilityParam ? builderVisibilityParam.value.trim() : '';
                targetNode.visibility.value = builderVisibilityValue ? builderVisibilityValue.value.trim() : '';
                delete targetNode.visibility.devices;
            } else if (targetNode.visibility.rule === 'device') {
                targetNode.visibility.devices = normalizeStringList(builderVisibilityDevices ? builderVisibilityDevices.value : '');
                delete targetNode.visibility.param;
                delete targetNode.visibility.value;
            } else {
                delete targetNode.visibility.param;
                delete targetNode.visibility.value;
                delete targetNode.visibility.devices;
            }
            targetNode.visibility.extraRule = builderVisibilityExtraRule ? builderVisibilityExtraRule.value || '' : '';
            if (targetNode.visibility.extraRule === 'url_param') {
                targetNode.visibility.extraParam = builderVisibilityExtraParam ? builderVisibilityExtraParam.value.trim() : '';
                targetNode.visibility.extraValue = builderVisibilityExtraValue ? builderVisibilityExtraValue.value.trim() : '';
                delete targetNode.visibility.extraDevices;
            } else if (targetNode.visibility.extraRule === 'device') {
                targetNode.visibility.extraDevices = normalizeStringList(builderVisibilityExtraDevices ? builderVisibilityExtraDevices.value : '');
                delete targetNode.visibility.extraParam;
                delete targetNode.visibility.extraValue;
            } else {
                delete targetNode.visibility.extraParam;
                delete targetNode.visibility.extraValue;
                delete targetNode.visibility.extraDevices;
            }
            if (builderVisibilityHint) {
                builderVisibilityHint.classList.remove('ft-page-builder__field-error');
                builderVisibilityHint.textContent = '当前条件已生效：命中规则后会' + (effect === 'show' ? '显示' : '隐藏') + '当前区块。' + (targetNode.visibility.extraRule ? ' 条件关系：' + (targetNode.visibility.logic === 'any' ? '任一满足' : '同时满足') + '。' : '');
            }
        }

        function parseBoxValue(value) {
            var tokens = String(value || '').trim().split(/\s+/).filter(Boolean);
            if (!tokens.length) {
                return ['', '', '', ''];
            }
            if (tokens.length === 1) {
                return [tokens[0], tokens[0], tokens[0], tokens[0]];
            }
            if (tokens.length === 2) {
                return [tokens[0], tokens[1], tokens[0], tokens[1]];
            }
            if (tokens.length === 3) {
                return [tokens[0], tokens[1], tokens[2], tokens[1]];
            }
            return [tokens[0], tokens[1], tokens[2], tokens[3]];
        }

        function compactBoxValues(values) {
            var top = String(values[0] || '').trim();
            var right = String(values[1] || '').trim();
            var bottom = String(values[2] || '').trim();
            var left = String(values[3] || '').trim();
            if (!top && !right && !bottom && !left) {
                return '';
            }
            top = top || '0';
            right = right || '0';
            bottom = bottom || '0';
            left = left || '0';
            if (top === right && top === bottom && top === left) {
                return top;
            }
            if (top === bottom && right === left) {
                return top + ' ' + right;
            }
            if (right === left) {
                return top + ' ' + right + ' ' + bottom;
            }
            return top + ' ' + right + ' ' + bottom + ' ' + left;
        }

        function fillSpacingFields(prefix, value) {
            var values = parseBoxValue(value);
            if (prefix === 'padding') {
                if (builderPaddingTop) {
                    builderPaddingTop.value = values[0];
                }
                if (builderPaddingRight) {
                    builderPaddingRight.value = values[1];
                }
                if (builderPaddingBottom) {
                    builderPaddingBottom.value = values[2];
                }
                if (builderPaddingLeft) {
                    builderPaddingLeft.value = values[3];
                }
                return;
            }
            if (builderMarginTop) {
                builderMarginTop.value = values[0];
            }
            if (builderMarginRight) {
                builderMarginRight.value = values[1];
            }
            if (builderMarginBottom) {
                builderMarginBottom.value = values[2];
            }
            if (builderMarginLeft) {
                builderMarginLeft.value = values[3];
            }
        }

        function readSpacingFields(prefix) {
            if (prefix === 'padding') {
                return compactBoxValues([
                    builderPaddingTop ? builderPaddingTop.value : '',
                    builderPaddingRight ? builderPaddingRight.value : '',
                    builderPaddingBottom ? builderPaddingBottom.value : '',
                    builderPaddingLeft ? builderPaddingLeft.value : ''
                ]);
            }
            return compactBoxValues([
                builderMarginTop ? builderMarginTop.value : '',
                builderMarginRight ? builderMarginRight.value : '',
                builderMarginBottom ? builderMarginBottom.value : '',
                builderMarginLeft ? builderMarginLeft.value : ''
            ]);
        }

        function parseBorderStyleConfig(style) {
            var result = {
                width: style && style.borderWidth ? String(style.borderWidth).trim() : '',
                style: style && style.borderStyle ? String(style.borderStyle).trim() : '',
                color: style && style.borderColor ? String(style.borderColor).trim() : ''
            };
            var shorthand = style && style.border ? String(style.border).trim() : '';
            if (!shorthand || shorthand === '0') {
                return result;
            }
            var parts = shorthand.split(/\s+/);
            if (!result.width && parts[0]) {
                result.width = parts[0];
            }
            if (!result.style && parts[1]) {
                result.style = parts[1];
            }
            if (!result.color && parts.length > 2) {
                result.color = parts.slice(2).join(' ');
            }
            return result;
        }

        function fillCommonStyleFields(style) {
            var border = parseBorderStyleConfig(style || {});
            if (builderCommonWidth) {
                builderCommonWidth.value = style && style.width ? style.width : '';
            }
            if (builderCommonMinHeight) {
                builderCommonMinHeight.value = style && style.minHeight ? style.minHeight : '';
            }
            if (builderCommonRadius) {
                builderCommonRadius.value = style && style.borderRadius ? style.borderRadius : '';
            }
            if (builderCommonBorderWidth) {
                builderCommonBorderWidth.value = border.width || '';
            }
            if (builderCommonBorderStyle) {
                builderCommonBorderStyle.value = border.style || '';
            }
            if (builderCommonBorderColor) {
                builderCommonBorderColor.value = border.color || '';
                syncEnhancedColorTextInput(builderCommonBorderColor, '#e2e8f0');
            }
            if (builderCommonBoxShadow) {
                builderCommonBoxShadow.value = style && style.boxShadow ? style.boxShadow : '';
            }
        }

        function applyCommonStyleConfig(node) {
            if (!node || !builderCommonStyleConfig || node.type === 'divider') {
                return;
            }
            var borderWidth = builderCommonBorderWidth ? builderCommonBorderWidth.value.trim() : '';
            var borderStyle = builderCommonBorderStyle ? builderCommonBorderStyle.value.trim() : '';
            var borderColor = builderCommonBorderColor ? builderCommonBorderColor.value.trim() : '';
            setNodeNestedValue(node, 'style', 'width', builderCommonWidth ? builderCommonWidth.value.trim() : '');
            setNodeNestedValue(node, 'style', 'minHeight', builderCommonMinHeight ? builderCommonMinHeight.value.trim() : '');
            setNodeNestedValue(node, 'style', 'borderRadius', builderCommonRadius ? builderCommonRadius.value.trim() : '');
            setNodeNestedValue(node, 'style', 'boxShadow', builderCommonBoxShadow ? builderCommonBoxShadow.value.trim() : '');
            setNodeNestedValue(node, 'style', 'borderWidth', borderWidth);
            setNodeNestedValue(node, 'style', 'borderStyle', borderStyle && borderStyle !== 'none' ? borderStyle : null);
            setNodeNestedValue(node, 'style', 'borderColor', borderColor);
            if (borderStyle === 'none') {
                setNodeNestedValue(node, 'style', 'border', '0');
            } else {
                setNodeNestedValue(node, 'style', 'border', null);
            }
        }

        function getChildCollectionKey(node) {
            if (!node || typeof node !== 'object') {
                return '';
            }
            if (Array.isArray(node.children) || node.type === 'section' || node.type === 'row') {
                return 'children';
            }
            if (Array.isArray(node.blocks) || node.type === 'column') {
                return 'blocks';
            }
            return '';
        }

        function ensureNode(node) {
            var normalized = node && typeof node === 'object' ? deepClone(node) : {};
            normalized.type = normalized.type || 'html';
            normalized.id = normalized.id || uniqueId(normalized.type);
            normalized.props = normalized.props && typeof normalized.props === 'object' ? normalized.props : {};
            normalized.style = normalized.style && typeof normalized.style === 'object' ? normalized.style : {};
            normalized.motion = normalized.motion && typeof normalized.motion === 'object' ? normalized.motion : {};
            normalized.responsive = normalized.responsive && typeof normalized.responsive === 'object' ? normalized.responsive : {};
            ['tablet', 'mobile'].forEach(function (device) {
                var config = normalized.responsive[device];
                config = config && typeof config === 'object' ? config : {};
                config.style = config.style && typeof config.style === 'object' ? config.style : {};
                var spanValue = parseInt(config.span, 10);
                if (spanValue >= 1 && spanValue <= 12) {
                    config.span = spanValue;
                } else {
                    delete config.span;
                }
                normalized.responsive[device] = config;
            });
            var childKey = getChildCollectionKey(normalized);
            if (childKey) {
                normalized[childKey] = Array.isArray(normalized[childKey]) ? normalized[childKey].map(ensureNode) : [];
            }
            return normalized;
        }

        function createDefaultPageTheme() {
            return {
                primary: '#2563eb',
                primaryContrast: '#ffffff',
                accent: '#0f172a',
                accentSoft: 'rgba(37, 99, 235, 0.08)',
                surface: '#ffffff',
                surfaceMuted: '#f8fafc',
                surfaceElevated: 'rgba(255, 255, 255, 0.96)',
                text: '#0f172a',
                textMuted: '#64748b',
                heading: '#0f172a',
                border: '#e2e8f0',
                heroGradient: 'linear-gradient(135deg, #0f172a 0%, #1d4ed8 52%, #38bdf8 100%)',
                accentGradient: 'linear-gradient(135deg, #2563eb 0%, #38bdf8 100%)',
                shadowSoft: '0 18px 40px rgba(15, 23, 42, 0.08)',
                shadowStrong: '0 28px 60px rgba(15, 23, 42, 0.20)',
                radiusCard: '24px',
                radiusSection: '28px',
                radiusPill: '999px',
                buttonStyle: 'solid',
                cardStyle: 'elevated',
                navStyle: 'glass'
            };
        }

        function normalizePageTheme(theme) {
            var normalized = createDefaultPageTheme();
            var source = theme && typeof theme === 'object' ? theme : {};
            Object.keys(normalized).forEach(function (key) {
                var value = source[key];
                if (value == null) {
                    return;
                }
                value = String(value).trim();
                if (value) {
                    normalized[key] = value;
                }
            });
            return normalized;
        }

        function buildThemeVariablePairs(theme) {
            var normalized = normalizePageTheme(theme);
            var map = {
                primary: '--mx-color-primary',
                primaryContrast: '--mx-color-primary-contrast',
                accent: '--mx-color-accent',
                accentSoft: '--mx-color-accent-soft',
                surface: '--mx-color-surface',
                surfaceMuted: '--mx-color-surface-muted',
                surfaceElevated: '--mx-color-surface-elevated',
                text: '--mx-color-text',
                textMuted: '--mx-color-text-muted',
                heading: '--mx-color-heading',
                border: '--mx-color-border',
                heroGradient: '--mx-gradient-hero',
                accentGradient: '--mx-gradient-accent',
                shadowSoft: '--mx-shadow-soft',
                shadowStrong: '--mx-shadow-strong',
                radiusCard: '--mx-radius-card',
                radiusSection: '--mx-radius-section',
                radiusPill: '--mx-radius-pill'
            };
            return Object.keys(map).map(function (key) {
                return map[key] + ':' + normalized[key];
            });
        }

        function buildThemeStyleAttribute(theme) {
            var pairs = buildThemeVariablePairs(theme);
            return pairs.length ? ' style="' + escapeHtml(pairs.join(';')) + '"' : '';
        }

        function buildThemeDataAttribute(theme) {
            var normalized = normalizePageTheme(theme);
            var map = {
                buttonStyle: 'data-button-style',
                cardStyle: 'data-card-style',
                navStyle: 'data-nav-style'
            };
            return Object.keys(map).map(function (key) {
                return normalized[key] ? ' ' + map[key] + '="' + escapeHtml(normalized[key]) + '"' : '';
            }).join('');
        }

        function createThemePresetMap() {
            return {
                'brand-blue': {
                    primary: '#2563eb',
                    accent: '#0f172a',
                    heading: '#0f172a',
                    surfaceMuted: '#f8fafc',
                    surfaceElevated: 'rgba(255, 255, 255, 0.96)',
                    text: '#0f172a',
                    textMuted: '#64748b',
                    border: '#dbe5f2',
                    heroGradient: 'linear-gradient(135deg, #0f172a 0%, #1d4ed8 52%, #38bdf8 100%)',
                    shadowSoft: '0 18px 40px rgba(15, 23, 42, 0.08)',
                    shadowStrong: '0 28px 60px rgba(15, 23, 42, 0.20)',
                    radiusCard: '24px',
                    radiusSection: '28px',
                    buttonStyle: 'solid',
                    cardStyle: 'elevated',
                    navStyle: 'glass'
                },
                'business-luxe': {
                    primary: '#b88a44',
                    accent: '#15110a',
                    heading: '#1d160d',
                    surfaceMuted: '#f8f3ea',
                    surfaceElevated: 'rgba(255, 249, 241, 0.95)',
                    text: '#2a2117',
                    textMuted: '#7c6647',
                    border: '#e8dcc7',
                    heroGradient: 'linear-gradient(135deg, #18120a 0%, #5e4321 48%, #c79a52 100%)',
                    shadowSoft: '0 18px 40px rgba(49, 33, 12, 0.10)',
                    shadowStrong: '0 30px 72px rgba(24, 18, 10, 0.24)',
                    radiusCard: '20px',
                    radiusSection: '24px',
                    buttonStyle: 'solid',
                    cardStyle: 'outline',
                    navStyle: 'solid'
                },
                'tech-future': {
                    primary: '#22d3ee',
                    accent: '#07111f',
                    heading: '#dffcff',
                    surfaceMuted: '#06111a',
                    surfaceElevated: 'rgba(8, 19, 33, 0.82)',
                    text: '#d5f9ff',
                    textMuted: '#7dc9d8',
                    border: '#173447',
                    heroGradient: 'linear-gradient(135deg, #050b14 0%, #0f2b46 42%, #22d3ee 100%)',
                    shadowSoft: '0 18px 40px rgba(10, 20, 35, 0.32)',
                    shadowStrong: '0 32px 80px rgba(10, 20, 35, 0.42)',
                    radiusCard: '26px',
                    radiusSection: '32px',
                    buttonStyle: 'glow',
                    cardStyle: 'glass',
                    navStyle: 'minimal'
                },
                'dark-mode': {
                    primary: '#60a5fa',
                    accent: '#020617',
                    heading: '#f8fafc',
                    surfaceMuted: '#020617',
                    surfaceElevated: 'rgba(15, 23, 42, 0.92)',
                    text: '#dbeafe',
                    textMuted: '#94a3b8',
                    border: '#1e293b',
                    heroGradient: 'linear-gradient(135deg, #020617 0%, #0f172a 56%, #1d4ed8 100%)',
                    shadowSoft: '0 18px 40px rgba(2, 6, 23, 0.35)',
                    shadowStrong: '0 30px 72px rgba(2, 6, 23, 0.5)',
                    radiusCard: '24px',
                    radiusSection: '28px',
                    buttonStyle: 'soft',
                    cardStyle: 'glass',
                    navStyle: 'solid'
                },
                'aurora-purple': {
                    primary: '#7c3aed',
                    accent: '#190f39',
                    heading: '#1f1147',
                    surfaceMuted: '#f6f3ff',
                    surfaceElevated: 'rgba(255, 255, 255, 0.96)',
                    text: '#1f1147',
                    textMuted: '#756a97',
                    border: '#ddd6fe',
                    heroGradient: 'linear-gradient(135deg, #1e1b4b 0%, #7c3aed 48%, #22d3ee 100%)',
                    shadowSoft: '0 20px 42px rgba(76, 29, 149, 0.12)',
                    shadowStrong: '0 30px 70px rgba(76, 29, 149, 0.24)',
                    radiusCard: '26px',
                    radiusSection: '30px',
                    buttonStyle: 'glow',
                    cardStyle: 'glass',
                    navStyle: 'glass'
                },
                'emerald-dark': {
                    primary: '#059669',
                    accent: '#052e2b',
                    heading: '#0f2f2d',
                    surfaceMuted: '#f3fbf8',
                    surfaceElevated: 'rgba(248, 250, 252, 0.96)',
                    text: '#0f2f2d',
                    textMuted: '#51706c',
                    border: '#ccebe4',
                    heroGradient: 'linear-gradient(135deg, #062f2b 0%, #047857 52%, #34d399 100%)',
                    shadowSoft: '0 18px 40px rgba(6, 95, 70, 0.10)',
                    shadowStrong: '0 30px 68px rgba(6, 95, 70, 0.22)',
                    radiusCard: '24px',
                    radiusSection: '28px',
                    buttonStyle: 'soft',
                    cardStyle: 'elevated',
                    navStyle: 'glass'
                },
                'slate-pro': {
                    primary: '#0f172a',
                    accent: '#020617',
                    heading: '#0f172a',
                    surfaceMuted: '#f4f7fb',
                    surfaceElevated: 'rgba(255, 255, 255, 0.98)',
                    text: '#0f172a',
                    textMuted: '#475569',
                    border: '#cbd5e1',
                    heroGradient: 'linear-gradient(135deg, #020617 0%, #1e293b 56%, #334155 100%)',
                    shadowSoft: '0 18px 44px rgba(15, 23, 42, 0.10)',
                    shadowStrong: '0 30px 70px rgba(2, 6, 23, 0.24)',
                    radiusCard: '22px',
                    radiusSection: '26px',
                    buttonStyle: 'solid',
                    cardStyle: 'outline',
                    navStyle: 'solid'
                }
            };
        }

        function ensureBuilderTheme() {
            builderState.theme = normalizePageTheme(builderState.theme);
            return builderState.theme;
        }

        function renderThemePreviewSwatch(preview, value, kind) {
            if (!preview) {
                return;
            }
            preview.style.background = '#ffffff';
            preview.style.boxShadow = 'inset 0 0 0 1px rgba(255, 255, 255, 0.4)';
            preview.style.borderRadius = '12px';
            preview.setAttribute('title', value || '');

            if (kind === 'gradient') {
                preview.style.background = value || 'linear-gradient(135deg, #0f172a 0%, #2563eb 100%)';
                return;
            }
            if (kind === 'shadow') {
                preview.style.background = 'linear-gradient(135deg, #ffffff 0%, #eef2ff 100%)';
                preview.style.boxShadow = value || '0 12px 24px rgba(15, 23, 42, 0.10)';
                return;
            }
            if (kind === 'radius') {
                preview.style.background = 'linear-gradient(135deg, #e0e7ff 0%, #bfdbfe 100%)';
                preview.style.borderRadius = value || '12px';
                return;
            }
            preview.style.background = value || '#ffffff';
        }

        function getThemeStyleLabel(type, value) {
            var maps = {
                buttonStyle: {
                    solid: '商务立体',
                    soft: '柔和胶囊',
                    glow: '科技光感'
                },
                cardStyle: {
                    elevated: '浮层卡片',
                    outline: '描边极简',
                    glass: '玻璃卡片'
                },
                navStyle: {
                    glass: '玻璃悬浮',
                    solid: '深色品牌条',
                    minimal: '极简透明'
                }
            };
            return (maps[type] && maps[type][value]) ? maps[type][value] : value;
        }

        function normalizeColorForPicker(value, fallback) {
            var color = String(value || '').trim();
            if (!color) {
                return fallback || '#2563eb';
            }
            var probe = document.createElement('span');
            probe.style.color = '';
            probe.style.color = color;
            if (!probe.style.color) {
                return fallback || '#2563eb';
            }
            var normalized = probe.style.color;
            var rgbMatch = normalized.match(/rgba?\((\d+),\s*(\d+),\s*(\d+)/i);
            if (!rgbMatch) {
                return fallback || '#2563eb';
            }
            return '#' + [rgbMatch[1], rgbMatch[2], rgbMatch[3]].map(function (part) {
                var hex = Math.max(0, Math.min(255, parseInt(part, 10) || 0)).toString(16);
                return hex.length === 1 ? '0' + hex : hex;
            }).join('');
        }

        function syncThemeColorPickers(theme) {
            pageBuilderThemeColorPickers.forEach(function (picker) {
                var key = picker.getAttribute('data-theme-color-picker');
                if (!key) {
                    return;
                }
                picker.value = normalizeColorForPicker(theme[key], picker.value || '#2563eb');
            });
        }

        function renderThemeSummary(theme) {
            if (!pageBuilderThemeSummary) {
                return;
            }
            pageBuilderThemeSummary.innerHTML = '' +
                '<span class="ft-page-builder__theme-summary-chip" title="' + escapeHtml(theme.primary) + '">' +
                    '<span class="ft-page-builder__theme-summary-swatch" style="background:' + escapeHtml(theme.primary) + ';"></span>' +
                    '<span>主色</span>' +
                '</span>' +
                '<span class="ft-page-builder__theme-summary-chip" title="' + escapeHtml(theme.accent) + '">' +
                    '<span class="ft-page-builder__theme-summary-swatch" style="background:' + escapeHtml(theme.accent) + ';"></span>' +
                    '<span>强调</span>' +
                '</span>' +
                '<span class="ft-page-builder__theme-summary-chip" title="' + escapeHtml(theme.heroGradient) + '">' +
                    '<span class="ft-page-builder__theme-summary-swatch" style="width:22px;border-radius:999px;background:' + escapeHtml(theme.heroGradient) + ';"></span>' +
                    '<span>' + escapeHtml(theme.radiusCard) + '</span>' +
                '</span>' +
                '<span class="ft-page-builder__theme-summary-chip" title="' + escapeHtml(getThemeStyleLabel('buttonStyle', theme.buttonStyle) + ' / ' + getThemeStyleLabel('cardStyle', theme.cardStyle) + ' / ' + getThemeStyleLabel('navStyle', theme.navStyle)) + '">' +
                    '<span>风格</span>' +
                    '<span>' + escapeHtml(getThemeStyleLabel('buttonStyle', theme.buttonStyle)) + '</span>' +
                '</span>';
        }

        function renderThemePreviews(theme) {
            pageBuilderThemePreviews.forEach(function (preview) {
                var key = preview.getAttribute('data-theme-preview');
                var kind = preview.getAttribute('data-theme-preview-kind') || 'color';
                if (!key) {
                    return;
                }
                renderThemePreviewSwatch(preview, theme[key] || '', kind);
            });
        }

        function renderThemePanel() {
            var theme = ensureBuilderTheme();
            pageBuilderThemeFields.forEach(function (field) {
                var key = field.getAttribute('data-theme-field');
                if (!key) {
                    return;
                }
                field.value = theme[key] || '';
            });
            syncThemeColorPickers(theme);
            renderThemePreviews(theme);
            renderThemeSummary(theme);
            if (pageBuilderThemeMeta) {
                pageBuilderThemeMeta.textContent = '当前主题：主色 ' + theme.primary + '，强调 ' + theme.accent + '，按钮 ' + getThemeStyleLabel('buttonStyle', theme.buttonStyle) + '，卡片 ' + getThemeStyleLabel('cardStyle', theme.cardStyle) + '，导航 ' + getThemeStyleLabel('navStyle', theme.navStyle) + '。配置会写入 layout_schema.theme。';
            }
        }

        function previewThemeDraft(patch) {
            var draftTheme = normalizePageTheme(Object.assign({}, ensureBuilderTheme(), patch || {}));
            syncThemeColorPickers(draftTheme);
            renderThemePreviews(draftTheme);
            renderThemeSummary(draftTheme);
            if (pageBuilderThemeMeta) {
                pageBuilderThemeMeta.textContent = '预览中：主色 ' + draftTheme.primary + '，强调 ' + draftTheme.accent + '，按钮 ' + getThemeStyleLabel('buttonStyle', draftTheme.buttonStyle) + '，卡片 ' + getThemeStyleLabel('cardStyle', draftTheme.cardStyle) + '，导航 ' + getThemeStyleLabel('navStyle', draftTheme.navStyle) + '。';
            }
        }

        function applyThemeUpdates(patch) {
            builderState.theme = Object.assign({}, ensureBuilderTheme(), patch || {});
            builderState.theme = normalizePageTheme(builderState.theme);
            refreshBuilder();
        }

        function resetPageTheme() {
            builderState.theme = createDefaultPageTheme();
            refreshBuilder();
        }

        function normalizeSchema(rawSchema) {
            var parsed;
            try {
                parsed = rawSchema ? JSON.parse(rawSchema) : {};
            } catch (error) {
                parsed = {};
            }
            if (Array.isArray(parsed)) {
                parsed = {sections: parsed};
            }
            if (!parsed || typeof parsed !== 'object') {
                parsed = {};
            }
            parsed.sections = Array.isArray(parsed.sections) ? parsed.sections.map(ensureNode) : [];
            parsed.theme = normalizePageTheme(parsed.theme);
            return parsed;
        }

        function createTemplateResponsiveConfig(tabletStyle, mobileStyle, tabletSpan, mobileSpan) {
            var responsive = {};
            if ((tabletStyle && typeof tabletStyle === 'object' && Object.keys(tabletStyle).length) || tabletSpan) {
                responsive.tablet = {
                    style: tabletStyle && typeof tabletStyle === 'object' ? tabletStyle : {}
                };
                if (tabletSpan) {
                    responsive.tablet.span = tabletSpan;
                }
            }
            if ((mobileStyle && typeof mobileStyle === 'object' && Object.keys(mobileStyle).length) || mobileSpan) {
                responsive.mobile = {
                    style: mobileStyle && typeof mobileStyle === 'object' ? mobileStyle : {}
                };
                if (mobileSpan) {
                    responsive.mobile.span = mobileSpan;
                }
            }
            return responsive;
        }

        function createTemplateContainedSection(children, style, innerWidth, options) {
            options = options || {};
            return {
                type: 'section',
                props: Object.assign({
                    contentWidth: 'contained',
                    innerWidth: innerWidth || '1180px'
                }, options.props || {}),
                style: Object.assign({
                    padding: '72px 0'
                }, style || {}),
                responsive: options.responsive || {},
                children: children || []
            };
        }

        function withNodeAnchor(node, anchor) {
            if (!node || typeof node !== 'object') {
                return node;
            }
            if (!node.props || typeof node.props !== 'object') {
                node.props = {};
            }
            node.props.anchor = anchor || '';
            return node;
        }

        function createTemplateHeadingNode(level, text, style, options) {
            options = options || {};
            return {
                type: 'heading',
                props: {
                    level: level || 'h2',
                    text: text || ''
                },
                style: style || {},
                responsive: options.responsive || {}
            };
        }

        function createTemplateTextNode(text, style, options) {
            options = options || {};
            return {
                type: 'text',
                props: {
                    text: text || ''
                },
                style: style || {},
                responsive: options.responsive || {}
            };
        }

        function createTemplateButtonNode(text, href, options) {
            options = options || {};
            return {
                type: 'button',
                props: {
                    text: text || '立即查看',
                    href: href || '#',
                    align: options.align || 'left',
                    variant: options.variant || 'solid'
                },
                style: Object.assign({
                    minHeight: options.minHeight || '46px',
                    padding: options.padding || '0 22px'
                }, options.style || {}),
                responsive: options.responsive || {}
            };
        }

        function createTemplateHtmlNode(html, style, options) {
            options = options || {};
            return {
                type: 'html',
                props: {
                    html: html || ''
                },
                style: style || {},
                responsive: options.responsive || {}
            };
        }

        function createTemplateFeatureCardColumn(span, config) {
            config = config || {};
            var points = Array.isArray(config.points) ? config.points : [];
            var pointsHtml = points.length ? '<ul style="margin:16px 0 0;padding:0 0 0 18px;color:' + escapeHtml(config.textColor || '#475569') + ';line-height:1.9;">' + points.map(function (item) {
                return '<li style="margin:0 0 8px;">' + escapeHtml(item) + '</li>';
            }).join('') + '</ul>' : '';
            return {
                type: 'column',
                props: {
                    span: span || 4
                },
                responsive: config.responsive || createTemplateResponsiveConfig({}, {}, 6, 12),
                blocks: [
                    createTemplateHtmlNode(
                        '<div style="height:100%;padding:28px;border-radius:26px;background:' + escapeHtml(config.background || '#ffffff') + ';border:1px solid ' + escapeHtml(config.borderColor || '#e2e8f0') + ';box-shadow:' + escapeHtml(config.boxShadow || '0 18px 38px rgba(15,23,42,.08)') + ';">' +
                            '<div style="display:inline-flex;align-items:center;height:32px;padding:0 12px;border-radius:999px;background:' + escapeHtml(config.badgeBackground || '#eff6ff') + ';color:' + escapeHtml(config.badgeColor || '#2563eb') + ';font-size:12px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;">' + escapeHtml(config.eyebrow || 'Core') + '</div>' +
                            '<h3 style="margin:18px 0 12px;font-size:22px;line-height:1.35;color:' + escapeHtml(config.titleColor || '#0f172a') + ';">' + escapeHtml(config.title || '') + '</h3>' +
                            '<p style="margin:0;color:' + escapeHtml(config.textColor || '#475569') + ';line-height:1.9;">' + escapeHtml(config.text || '') + '</p>' +
                            pointsHtml +
                        '</div>'
                    )
                ]
            };
        }

        function createTemplateCardColumn(span, title, text, tone) {
            var toneMap = {
                blue: {
                    background: '#eff6ff',
                    border: '#bfdbfe',
                    title: '#1d4ed8',
                    text: '#475569'
                },
                dark: {
                    background: '#0f172a',
                    border: 'rgba(148,163,184,.22)',
                    title: '#ffffff',
                    text: 'rgba(255,255,255,.78)'
                },
                slate: {
                    background: '#f8fafc',
                    border: '#e2e8f0',
                    title: '#0f172a',
                    text: '#64748b'
                }
            };
            var theme = toneMap[tone] || toneMap.slate;
            return {
                type: 'column',
                props: {
                    span: span || 4
                },
                blocks: [
                    {
                        type: 'html',
                        props: {
                            html: '<div style="height:100%;padding:28px;border-radius:22px;background:' + theme.background + ';border:1px solid ' + theme.border + ';box-shadow:0 12px 28px rgba(15,23,42,.05);"><h3 style="margin:0 0 12px;font-size:20px;line-height:1.3;color:' + theme.title + ';">' + escapeHtml(title || '') + '</h3><p style="margin:0;color:' + theme.text + ';line-height:1.85;">' + escapeHtml(text || '') + '</p></div>'
                        }
                    }
                ]
            };
        }

        function createTemplateNavigationSection(title, items, ctaText, ctaHref) {
            return createTemplateContainedSection([
                {
                    type: 'navigation',
                    props: {
                        title: title || '品牌站点',
                        layout: 'horizontal',
                        ctaText: ctaText || '立即咨询',
                        ctaHref: ctaHref || '/contact',
                        items: items || []
                    }
                }
            ], {
                padding: '18px 0 12px'
            }, '1180px');
        }

        function createTemplateFaqSection(title, intro, items) {
            return createTemplateContainedSection([
                {
                    type: 'faq',
                    props: {
                        title: title || '常见问题',
                        intro: intro || '',
                        columns: '2',
                        items: items || []
                    }
                }
            ], {
                padding: '72px 0'
            }, '980px');
        }

        function createTemplateStatsSection(title, intro, items, sectionStyle) {
            return createTemplateContainedSection([
                {
                    type: 'stats',
                    props: {
                        title: title || '',
                        intro: intro || '',
                        columns: String(Math.min(Math.max((items || []).length || 4, 2), 4)),
                        items: items || []
                    }
                }
            ], Object.assign({
                padding: '56px 0'
            }, sectionStyle || {}), '1180px');
        }

        function createTemplateCtaSection(config) {
            config = config || {};
            return createTemplateContainedSection([
                {
                    type: 'cta',
                    props: {
                        eyebrow: config.eyebrow || 'Ready To Start',
                        title: config.title || '准备开始下一步转化了吗？',
                        description: config.description || '这里适合放预约咨询、立即试用、获取报价、提交需求等最终动作。',
                        primaryText: config.primaryText || '立即咨询',
                        primaryHref: config.primaryHref || '/contact',
                        secondaryText: config.secondaryText || '查看案例',
                        secondaryHref: config.secondaryHref || '/cases',
                        align: config.align || 'left',
                        actionsAlign: config.actionsAlign || '',
                        primaryVariant: config.primaryVariant || 'solid',
                        secondaryVariant: config.secondaryVariant || 'outline'
                    },
                    style: {
                        padding: '34px',
                        borderRadius: '28px',
                        background: config.background || 'linear-gradient(135deg, #0f172a 0%, #1e293b 100%)',
                        ctaButtonMinHeight: config.buttonMinHeight || '48px',
                        hoverBackground: config.hoverBackground || '#ffffff',
                        hoverColor: config.hoverColor || '#0f172a',
                        hoverBorderColor: config.hoverBorderColor || '#ffffff',
                        hoverBoxShadow: config.hoverBoxShadow || '0 16px 36px rgba(15, 23, 42, 0.24)'
                    }
                }
            ], {
                padding: '72px 0'
            }, '1180px');
        }

        function createTemplateLeadFormSection(title, text) {
            return createTemplateContainedSection([
                {
                    type: 'row',
                    style: {
                        gap: '28px',
                        alignItems: 'center'
                    },
                    children: [
                        {
                            type: 'column',
                            props: {
                                span: 7
                            },
                            blocks: [
                                createTemplateHeadingNode('h2', title || '留下需求，顾问尽快联系你', {
                                    margin: '0 0 14px'
                                }),
                                createTemplateTextNode(text || '适合预约演示、提交需求、方案咨询、商务合作等场景，右侧可继续替换成真实表单或线索组件。', {
                                    color: '#64748b',
                                    lineHeight: '1.9'
                                })
                            ]
                        },
                        {
                            type: 'column',
                            props: {
                                span: 5
                            },
                            blocks: createContainerQuickNodes('column-form')
                        }
                    ]
                }
            ], {
                padding: '72px 0',
                background: 'linear-gradient(135deg, #eff6ff 0%, #ffffff 100%)'
            }, '1180px');
        }

        function createCorporateHomeTemplateSections() {
            return [
                createTemplateNavigationSection('墨小智内容中台', [
                    {text: '首页', href: '/'},
                    {text: '解决方案', href: '/solutions', children: [
                        {text: '品牌官网', href: '/solutions/brand'},
                        {text: '营销落地页', href: '/solutions/landing'}
                    ]},
                    {text: '产品服务', href: '/services', children: [
                        {text: '页面搭建', href: '/services/pages'},
                        {text: '内容运营', href: '/services/content'}
                    ]},
                    {text: '案例中心', href: '/cases'},
                    {text: '联系我们', href: '/contact'}
                ], '预约演示', '/contact'),
                createTemplateContainedSection([
                    {
                        type: 'row',
                        style: {
                            gap: '28px',
                            alignItems: 'stretch'
                        },
                        responsive: createTemplateResponsiveConfig({
                            gap: '22px'
                        }, {
                            gap: '18px'
                        }),
                        children: [
                            {
                                type: 'column',
                                props: {
                                    span: 7
                                },
                                responsive: createTemplateResponsiveConfig({}, {}, 12, 12),
                                blocks: [
                                    createTemplateHtmlNode('<div style="display:inline-flex;align-items:center;height:34px;padding:0 14px;border-radius:999px;background:rgba(37,99,235,.10);color:#1d4ed8;font-size:12px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;">Enterprise Content Experience</div>', {
                                        margin: '0 0 18px'
                                    }),
                                    createTemplateHeadingNode('h1', '一套更像商用成品的官网首页，帮助品牌更快完成展示与转化', {
                                        margin: '0 0 18px',
                                        fontSize: '58px',
                                        lineHeight: '1.08',
                                        color: '#0f172a',
                                        letterSpacing: '-0.03em',
                                        maxWidth: '720px'
                                    }, {
                                        responsive: createTemplateResponsiveConfig({
                                            fontSize: '48px',
                                            maxWidth: '100%'
                                        }, {
                                            fontSize: '34px',
                                            lineHeight: '1.18'
                                        })
                                    }),
                                    createTemplateTextNode('把品牌定位、核心卖点、解决方案、案例证明和咨询转化整理成一张完整首页。导入后只需要替换文案、图片和数据，就能快速进入交付状态。', {
                                        margin: '0 0 26px',
                                        fontSize: '18px',
                                        lineHeight: '1.95',
                                        color: '#475569',
                                        maxWidth: '680px'
                                    }, {
                                        responsive: createTemplateResponsiveConfig({
                                            fontSize: '17px'
                                        }, {
                                            fontSize: '16px',
                                            lineHeight: '1.85'
                                        })
                                    }),
                                    createTemplateButtonNode('预约品牌演示', '/contact', {
                                        align: 'left',
                                        variant: 'solid',
                                        padding: '0 24px',
                                        style: {
                                            display: 'inline-flex',
                                            margin: '0 14px 14px 0',
                                            background: '#2563eb',
                                            color: '#ffffff',
                                            borderRadius: '14px',
                                            boxShadow: '0 16px 30px rgba(37,99,235,.22)',
                                            hoverBackground: '#1d4ed8'
                                        },
                                        responsive: createTemplateResponsiveConfig({}, {
                                            width: '100%',
                                            margin: '0 0 12px'
                                        })
                                    }),
                                    createTemplateButtonNode('查看解决方案', '/solutions', {
                                        align: 'left',
                                        variant: 'outline',
                                        padding: '0 24px',
                                        style: {
                                            display: 'inline-flex',
                                            margin: '0 0 14px',
                                            borderRadius: '14px',
                                            color: '#0f172a',
                                            borderColor: '#cbd5e1',
                                            hoverBackground: '#ffffff',
                                            hoverBorderColor: '#94a3b8'
                                        },
                                        responsive: createTemplateResponsiveConfig({}, {
                                            width: '100%'
                                        })
                                    }),
                                    createTemplateHtmlNode(
                                        '<div style="display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:14px;margin-top:18px;">' +
                                            '<div style="padding:16px 18px;border-radius:18px;background:#ffffff;border:1px solid rgba(148,163,184,.22);box-shadow:0 12px 28px rgba(15,23,42,.06);"><div style="font-size:24px;font-weight:700;color:#0f172a;">120+</div><div style="margin-top:6px;color:#64748b;line-height:1.7;">品牌页与活动页模板资产</div></div>' +
                                            '<div style="padding:16px 18px;border-radius:18px;background:#ffffff;border:1px solid rgba(148,163,184,.22);box-shadow:0 12px 28px rgba(15,23,42,.06);"><div style="font-size:24px;font-weight:700;color:#0f172a;">3 端</div><div style="margin-top:6px;color:#64748b;line-height:1.7;">PC、平板、手机同步适配</div></div>' +
                                            '<div style="padding:16px 18px;border-radius:18px;background:#ffffff;border:1px solid rgba(148,163,184,.22);box-shadow:0 12px 28px rgba(15,23,42,.06);"><div style="font-size:24px;font-weight:700;color:#0f172a;">48h</div><div style="margin-top:6px;color:#64748b;line-height:1.7;">从模板到首版交付常见周期</div></div>' +
                                        '</div>',
                                        {},
                                        {
                                            responsive: createTemplateResponsiveConfig({
                                                gridTemplateColumns: 'repeat(3,minmax(0,1fr))'
                                            }, {
                                                gridTemplateColumns: '1fr'
                                            })
                                        }
                                    )
                                ]
                            },
                            {
                                type: 'column',
                                props: {
                                    span: 5
                                },
                                responsive: createTemplateResponsiveConfig({}, {}, 12, 12),
                                blocks: [
                                    createTemplateHtmlNode(
                                        '<div style="height:100%;padding:30px;border-radius:30px;background:linear-gradient(160deg,#0f172a 0%,#1e3a8a 58%,#38bdf8 100%);box-shadow:0 28px 56px rgba(15,23,42,.22);color:#ffffff;">' +
                                            '<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:22px;"><div><div style="font-size:14px;opacity:.72;">本周线索看板</div><div style="margin-top:6px;font-size:30px;font-weight:700;">品牌官网升级计划</div></div><div style="padding:8px 12px;border-radius:999px;background:rgba(255,255,255,.16);font-size:12px;">可视化交付</div></div>' +
                                            '<div style="display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:14px;margin-bottom:18px;">' +
                                                '<div style="padding:18px;border-radius:20px;background:rgba(255,255,255,.10);backdrop-filter:blur(10px);"><div style="font-size:13px;opacity:.72;">首页完成度</div><div style="margin-top:8px;font-size:34px;font-weight:700;">92%</div></div>' +
                                                '<div style="padding:18px;border-radius:20px;background:rgba(255,255,255,.10);backdrop-filter:blur(10px);"><div style="font-size:13px;opacity:.72;">转化动作</div><div style="margin-top:8px;font-size:34px;font-weight:700;">5</div></div>' +
                                            '</div>' +
                                            '<div style="padding:20px;border-radius:22px;background:rgba(255,255,255,.10);backdrop-filter:blur(10px);">' +
                                                '<div style="font-size:15px;font-weight:700;margin-bottom:14px;">建议保留的首页信息层</div>' +
                                                '<div style="display:grid;gap:12px;">' +
                                                    '<div style="display:flex;justify-content:space-between;gap:12px;"><span style="opacity:.78;">品牌价值定位</span><strong>Hero 首屏</strong></div>' +
                                                    '<div style="display:flex;justify-content:space-between;gap:12px;"><span style="opacity:.78;">解决方案说明</span><strong>3 段能力卡片</strong></div>' +
                                                    '<div style="display:flex;justify-content:space-between;gap:12px;"><span style="opacity:.78;">案例与证明</span><strong>图库 + 数据区</strong></div>' +
                                                    '<div style="display:flex;justify-content:space-between;gap:12px;"><span style="opacity:.78;">最终转化动作</span><strong>CTA + 表单</strong></div>' +
                                                '</div>' +
                                            '</div>' +
                                        '</div>'
                                    )
                                ]
                            }
                        ]
                    }
                ], {
                    padding: '30px 0 72px',
                    background: 'linear-gradient(180deg, #eff6ff 0%, #ffffff 82%)'
                }, '1240px', {
                    responsive: createTemplateResponsiveConfig({
                        padding: '26px 0 64px'
                    }, {
                        padding: '22px 0 52px'
                    })
                }),
                createTemplateContainedSection([
                    createTemplateHeadingNode('h2', '首页先把这三层商业信息讲透', {
                        textAlign: 'center',
                        margin: '0 0 14px',
                        fontSize: '38px',
                        lineHeight: '1.2'
                    }, {
                        responsive: createTemplateResponsiveConfig({
                            fontSize: '32px'
                        }, {
                            fontSize: '28px'
                        })
                    }),
                    createTemplateTextNode('从价值感知、服务能力到案例证明，模板先给你完整的叙事顺序，方便后续直接替换真实业务内容。', {
                        textAlign: 'center',
                        color: '#64748b',
                        maxWidth: '760px',
                        margin: '0 auto 32px',
                        lineHeight: '1.9'
                    }),
                    {
                        type: 'row',
                        style: {
                            gap: '20px'
                        },
                        responsive: createTemplateResponsiveConfig({
                            gap: '18px'
                        }, {
                            gap: '16px'
                        }),
                        children: [
                            createTemplateFeatureCardColumn(4, {
                                eyebrow: 'Value',
                                title: '品牌价值一句话讲清',
                                text: '在首屏明确客户对象、业务定位和交付结果，让访客在 5 秒内知道你是做什么的。',
                                points: ['主标题聚焦结果', '副标题补充行业与方法', '按钮直达咨询或方案'],
                                background: '#ffffff',
                                borderColor: '#dbeafe'
                            }),
                            createTemplateFeatureCardColumn(4, {
                                eyebrow: 'Solution',
                                title: '解决方案分层展示',
                                text: '把服务模块拆成 3 到 6 个清晰能力点，每段都能承接用户常见问题。',
                                points: ['适配官网首页结构', '支持图文、数据、FAQ 混搭', '便于继续切模型来源'],
                                background: '#f8fafc',
                                borderColor: '#e2e8f0',
                                badgeBackground: '#e2e8f0',
                                badgeColor: '#334155'
                            }),
                            createTemplateFeatureCardColumn(4, {
                                eyebrow: 'Proof',
                                title: '案例和数据同时建立信任',
                                text: '将典型案例、客户评价、结果数据和交付流程一起放在中后段，形成完整证明闭环。',
                                points: ['支持案例图库承接', '支持统计区强化可信度', '底部 CTA 做最终转化'],
                                background: '#0f172a',
                                borderColor: 'rgba(148,163,184,.22)',
                                badgeBackground: 'rgba(255,255,255,.12)',
                                badgeColor: '#ffffff',
                                titleColor: '#ffffff',
                                textColor: 'rgba(255,255,255,.82)',
                                boxShadow: '0 18px 42px rgba(15,23,42,.18)'
                            })
                        ]
                    }
                ], {
                    padding: '0 0 72px'
                }, '1180px', {
                    responsive: createTemplateResponsiveConfig({}, {
                        padding: '0 0 52px'
                    })
                }),
                createTemplateStatsSection('让首页既好看，也能承接转化', '常见的商用首页，不只是摆内容，更重要的是建立信任、展示能力并引导行动。', [
                    {label: '首屏转化位', value: '2', suffix: '个', description: '主按钮 + 次按钮同步承接'},
                    {label: '能力区块', value: '3', suffix: '层', description: '价值、解决方案、案例证明'},
                    {label: '内容结构', value: '模块化', suffix: '', description: '便于团队协作编辑'},
                    {label: '适配终端', value: 'PC', suffix: '/Pad/Phone', description: '显式包含平板与手机配置'}
                ], {
                    background: '#f8fafc'
                }),
                createTemplateContainedSection([
                    createTemplateHeadingNode('h2', '案例区先用高质感卡片承接', {
                        textAlign: 'center',
                        margin: '0 0 14px',
                        fontSize: '36px'
                    }, {
                        responsive: createTemplateResponsiveConfig({
                            fontSize: '32px'
                        }, {
                            fontSize: '28px'
                        })
                    }),
                    createTemplateTextNode('这一区建议放代表性项目、落地页截图或典型行业方案，先营造成熟交付的感知，再引导进入详情。', {
                        textAlign: 'center',
                        color: '#64748b',
                        maxWidth: '760px',
                        margin: '0 auto 30px',
                        lineHeight: '1.9'
                    }),
                    {
                        type: 'gallery',
                        props: {
                            title: '案例预览',
                            subtitle: '支持先用手动案例起稿，后续再切换到模型列表来源。',
                            source_type: 'manual',
                            columns: '3',
                            gap: '18px',
                            items: [
                                {title: '品牌官网升级', image: 'https://dummyimage.com/960x720/e2e8f0/0f172a&text=Brand+Revamp', url: '/cases/brand-home'},
                                {title: '增长型落地页', image: 'https://dummyimage.com/960x720/dbeafe/1d4ed8&text=Growth+Landing', url: '/cases/landing'},
                                {title: 'SaaS 产品官网', image: 'https://dummyimage.com/960x720/f8fafc/334155&text=Product+Site', url: '/cases/product'}
                            ]
                        }
                    }
                ], {
                    padding: '0 0 72px'
                }, '1180px', {
                    responsive: createTemplateResponsiveConfig({}, {
                        padding: '0 0 52px'
                    })
                }),
                createTemplateFaqSection('首页常见问题', '适合放在首页底部，消除咨询前的常见疑问。', [
                    {question: '模板导入后还能继续改吗？', answer: '可以，导入只是先生成一套完整结构，后续仍然可以继续删改区块、样式、数据来源和响应式设置。'},
                    {question: '这套首页适合哪些业务场景？', answer: '适合品牌官网、企业站、SaaS 产品站、服务型官网和需要展示案例能力的商业首页。'},
                    {question: '模板里的案例区能切成真实数据吗？', answer: '可以，当前先用高质量手动案例起稿，后续可以切到模型列表来源或详情页跳转。'},
                    {question: 'PC、平板和手机都能单独调吗？', answer: '可以，模板已预置关键区块的响应式结构，导入后仍能继续单独微调字体、间距和栅格跨度。'}
                ]),
                createTemplateCtaSection({
                    eyebrow: 'Corporate Home',
                    title: '首页结构已经完整就位，下一步只需要替换你的品牌内容',
                    description: '优先替换首屏标题、价值说明、案例和 CTA，就能把这一页快速改造成接近商用品质的正式首页。',
                    primaryText: '开始替换内容',
                    primaryHref: '/contact',
                    secondaryText: '查看解决方案',
                    secondaryHref: '/solutions',
                    align: 'left'
                })
            ];
        }

        function createServiceLandingTemplateSections() {
            return [
                createTemplateContainedSection([
                    {
                        type: 'row',
                        style: {
                            gap: '28px',
                            alignItems: 'center'
                        },
                        responsive: createTemplateResponsiveConfig({
                            gap: '22px'
                        }, {
                            gap: '18px'
                        }),
                        children: [
                            {
                                type: 'column',
                                props: {
                                    span: 6
                                },
                                responsive: createTemplateResponsiveConfig({}, {}, 12, 12),
                                blocks: [
                                    createTemplateHtmlNode('<div style="display:inline-flex;align-items:center;height:34px;padding:0 14px;border-radius:999px;background:rgba(15,23,42,.08);color:#0f172a;font-size:12px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;">Service Conversion Template</div>', {
                                        margin: '0 0 18px'
                                    }),
                                    createTemplateHeadingNode('h1', '为单个服务方案搭一张更像商用成品的高转化落地页', {
                                        margin: '0 0 18px',
                                        fontSize: '54px',
                                        lineHeight: '1.1',
                                        letterSpacing: '-0.03em'
                                    }, {
                                        responsive: createTemplateResponsiveConfig({
                                            fontSize: '44px'
                                        }, {
                                            fontSize: '34px',
                                            lineHeight: '1.16'
                                        })
                                    }),
                                    createTemplateTextNode('适合咨询服务、代运营、定制开发、培训课程和企业方案等场景。模板会先把价值陈述、信任证明、服务流程和表单承接一次搭好。', {
                                        margin: '0 0 24px',
                                        color: '#475569',
                                        fontSize: '18px',
                                        lineHeight: '1.9'
                                    }, {
                                        responsive: createTemplateResponsiveConfig({
                                            fontSize: '17px'
                                        }, {
                                            fontSize: '16px'
                                        })
                                    }),
                                    createTemplateButtonNode('获取专属方案', '/contact', {
                                        align: 'left',
                                        variant: 'solid',
                                        style: {
                                            display: 'inline-flex',
                                            margin: '0 14px 14px 0',
                                            background: '#2563eb',
                                            color: '#ffffff',
                                            borderRadius: '14px',
                                            boxShadow: '0 16px 30px rgba(37,99,235,.22)',
                                            hoverBackground: '#1d4ed8'
                                        },
                                        responsive: createTemplateResponsiveConfig({}, {
                                            width: '100%',
                                            margin: '0 0 12px'
                                        })
                                    }),
                                    createTemplateButtonNode('查看交付案例', '/cases', {
                                        align: 'left',
                                        variant: 'outline',
                                        style: {
                                            display: 'inline-flex',
                                            borderRadius: '14px',
                                            color: '#0f172a',
                                            borderColor: '#cbd5e1',
                                            hoverBackground: '#ffffff'
                                        },
                                        responsive: createTemplateResponsiveConfig({}, {
                                            width: '100%'
                                        })
                                    })
                                ]
                            },
                            {
                                type: 'column',
                                props: {
                                    span: 6
                                },
                                responsive: createTemplateResponsiveConfig({}, {}, 12, 12),
                                blocks: [
                                    createTemplateHtmlNode(
                                        '<div style="height:100%;padding:30px;border-radius:30px;background:#ffffff;border:1px solid rgba(148,163,184,.22);box-shadow:0 28px 56px rgba(15,23,42,.12);">' +
                                            '<div style="display:flex;justify-content:space-between;gap:16px;align-items:flex-start;margin-bottom:20px;"><div><div style="font-size:13px;color:#64748b;">落地页推荐结构</div><div style="margin-top:8px;font-size:30px;font-weight:700;color:#0f172a;">价值 -> 证明 -> 转化</div></div><div style="padding:8px 12px;border-radius:999px;background:#eff6ff;color:#2563eb;font-size:12px;font-weight:700;">高转化骨架</div></div>' +
                                            '<div style="display:grid;gap:14px;">' +
                                                '<div style="padding:18px;border-radius:20px;background:#f8fafc;"><div style="font-size:14px;font-weight:700;color:#0f172a;">01 价值首屏</div><div style="margin-top:8px;color:#64748b;line-height:1.8;">强调你能解决什么问题、适合谁、为什么现在就要咨询。</div></div>' +
                                                '<div style="padding:18px;border-radius:20px;background:#f8fafc;"><div style="font-size:14px;font-weight:700;color:#0f172a;">02 信任与流程</div><div style="margin-top:8px;color:#64748b;line-height:1.8;">用案例、交付步骤、服务边界和顾问机制减少顾虑。</div></div>' +
                                                '<div style="padding:18px;border-radius:20px;background:#0f172a;color:#ffffff;"><div style="font-size:14px;font-weight:700;">03 中段表单承接</div><div style="margin-top:8px;color:rgba(255,255,255,.78);line-height:1.8;">在用户信任建立后立即出现咨询入口，而不是只留在页尾。</div></div>' +
                                            '</div>' +
                                        '</div>'
                                    )
                                ]
                            }
                        ]
                    }
                ], {
                    padding: '84px 0',
                    background: 'linear-gradient(135deg, #eff6ff 0%, #ffffff 100%)'
                }, '1180px', {
                    responsive: createTemplateResponsiveConfig({
                        padding: '72px 0'
                    }, {
                        padding: '56px 0'
                    })
                }),
                createTemplateStatsSection('服务页先回答 4 个商业问题', '客户进入页面后，通常会先看适合对象、能解决什么、如何合作以及多久能收到响应。', [
                    {label: '适合对象', value: '明确', suffix: '', description: '首屏就说明服务适用客户'},
                    {label: '合作路径', value: '3', suffix: '步', description: '咨询、方案、交付一目了然'},
                    {label: '顾问响应', value: '30', suffix: '分钟', description: '可替换为你的承诺时效'},
                    {label: '转化入口', value: '2', suffix: '处', description: '中段和底部同步承接'}
                ]),
                createTemplateContainedSection([
                    createTemplateHeadingNode('h2', '这张落地页推荐的内容顺序', {
                        textAlign: 'center',
                        margin: '0 0 14px',
                        fontSize: '38px'
                    }, {
                        responsive: createTemplateResponsiveConfig({
                            fontSize: '32px'
                        }, {
                            fontSize: '28px'
                        })
                    }),
                    createTemplateTextNode('价值说明、服务模块、交付流程、客户证明、FAQ 和表单承接，是大多数服务页最稳的商业骨架。', {
                        textAlign: 'center',
                        color: '#64748b',
                        maxWidth: '760px',
                        margin: '0 auto 30px'
                    }),
                    {
                        type: 'row',
                        style: {
                            gap: '20px'
                        },
                        responsive: createTemplateResponsiveConfig({
                            gap: '18px'
                        }, {
                            gap: '16px'
                        }),
                        children: [
                            createTemplateFeatureCardColumn(4, {
                                eyebrow: 'Value',
                                title: '讲清为什么选择你',
                                text: '让用户快速理解你的服务结果、适合对象和合作优势。',
                                points: ['结果导向标题', '场景化副标题', '主按钮直达咨询']
                            }),
                            createTemplateFeatureCardColumn(4, {
                                eyebrow: 'Trust',
                                title: '把证明链补完整',
                                text: '用案例、流程、顾问机制和交付标准消除购买前顾虑。',
                                points: ['项目流程清晰', '顾问角色明确', '支持继续加客户证言'],
                                background: '#f8fafc',
                                borderColor: '#e2e8f0',
                                badgeBackground: '#e2e8f0',
                                badgeColor: '#334155'
                            }),
                            createTemplateFeatureCardColumn(4, {
                                eyebrow: 'Convert',
                                title: '把咨询入口放在高意向位置',
                                text: '中段和底部都保留线索承接，让感兴趣的访客立刻行动。',
                                points: ['中段表单承接', '底部 CTA 再次转化', '适合销售和顾问业务'],
                                background: '#0f172a',
                                borderColor: 'rgba(148,163,184,.22)',
                                badgeBackground: 'rgba(255,255,255,.12)',
                                badgeColor: '#ffffff',
                                titleColor: '#ffffff',
                                textColor: 'rgba(255,255,255,.82)',
                                boxShadow: '0 18px 42px rgba(15,23,42,.18)'
                            })
                        ]
                    }
                ], {
                    padding: '72px 0',
                    background: '#f8fafc'
                }, '1180px'),
                createTemplateContainedSection([
                    {
                        type: 'row',
                        style: {
                            gap: '24px',
                            alignItems: 'center'
                        },
                        responsive: createTemplateResponsiveConfig({
                            gap: '20px'
                        }, {
                            gap: '18px'
                        }),
                        children: [
                            {
                                type: 'column',
                                props: {
                                    span: 5
                                },
                                responsive: createTemplateResponsiveConfig({}, {}, 12, 12),
                                blocks: [
                                    createTemplateHeadingNode('h2', '把服务流程写成客户能理解的交付路径', {
                                        margin: '0 0 16px',
                                        fontSize: '34px',
                                        lineHeight: '1.24'
                                    }, {
                                        responsive: createTemplateResponsiveConfig({
                                            fontSize: '30px'
                                        }, {
                                            fontSize: '26px'
                                        })
                                    }),
                                    createTemplateTextNode('这一段适合展示服务内容、时间周期、合作方式和关键里程碑。左侧说明业务逻辑，右侧放视觉主图或案例封面。', {
                                        color: '#64748b',
                                        lineHeight: '1.9',
                                        margin: '0 0 20px'
                                    }),
                                    createTemplateHtmlNode(
                                        '<div style="padding:22px;border-radius:24px;background:#ffffff;border:1px solid #e2e8f0;box-shadow:0 18px 38px rgba(15,23,42,.06);">' +
                                            '<div style="display:grid;gap:14px;">' +
                                                '<div><div style="font-size:12px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#2563eb;">Step 1</div><div style="margin-top:6px;font-size:18px;font-weight:700;color:#0f172a;">业务诊断与目标确认</div></div>' +
                                                '<div><div style="font-size:12px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#2563eb;">Step 2</div><div style="margin-top:6px;font-size:18px;font-weight:700;color:#0f172a;">方案输出与交付排期</div></div>' +
                                                '<div><div style="font-size:12px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#2563eb;">Step 3</div><div style="margin-top:6px;font-size:18px;font-weight:700;color:#0f172a;">执行协同与效果复盘</div></div>' +
                                            '</div>' +
                                        '</div>'
                                    )
                                ]
                            },
                            {
                                type: 'column',
                                props: {
                                    span: 7
                                },
                                responsive: createTemplateResponsiveConfig({}, {}, 12, 12),
                                blocks: [
                                    {
                                        type: 'image',
                                        props: {
                                            src: 'https://dummyimage.com/1280x840/e2e8f0/0f172a&text=Service+Workflow',
                                            alt: '服务交付流程示意图',
                                            align: 'center'
                                        },
                                        style: {
                                            width: '100%',
                                            borderRadius: '28px',
                                            minHeight: '360px',
                                            objectFit: 'cover',
                                            boxShadow: '0 26px 50px rgba(15,23,42,.12)',
                                            hoverTransform: 'scale(1.02)',
                                            hoverBoxShadow: '0 30px 56px rgba(15,23,42,.16)'
                                        }
                                    }
                                ]
                            }
                        ]
                    }
                ], {
                    padding: '0 0 72px'
                }, '1180px'),
                createTemplateLeadFormSection('把流量直接承接成线索', '这类页面建议把咨询表单放在中段，用户看完服务模块后能立刻提交需求。'),
                createTemplateFaqSection('服务落地页 FAQ', '把购买前最常问的流程、报价、周期和交付问题直接讲清。', [
                    {question: '服务落地页最少要有哪几段？', answer: '建议至少包含价值首屏、服务说明、信任证明、FAQ 和表单承接。'},
                    {question: '这套模板后面能改成活动页吗？', answer: '可以，落地页和活动页的结构相近，替换标题、视觉和 CTA 即可。'},
                    {question: '表单一定要放中间吗？', answer: '不一定，但建议中段就出现一次，底部再放一次，转化会更顺。'},
                    {question: '能接视频或案例吗？', answer: '可以，这套模板里留的是结构骨架，后续可以继续替换成视频、图库、案例列表等。'}
                ]),
                createTemplateCtaSection({
                    eyebrow: 'Service Landing',
                    title: '这张页面已经具备服务转化骨架',
                    description: '下一步重点替换服务名称、适合客户、成功案例和表单文案，就能快速变成正式可投放的服务页。',
                    primaryText: '开始改成我的服务',
                    primaryHref: '/contact',
                    secondaryText: '预约顾问演示',
                    secondaryHref: '/demo',
                    align: 'left'
                })
            ];
        }

        function createProductDetailTemplateSections() {
            return [
                createTemplateNavigationSection('产品中心', [
                    {text: '产品首页', href: '/products'},
                    {text: '核心功能', href: '/products/features'},
                    {text: '部署方式', href: '/products/deploy'},
                    {text: '价格方案', href: '/products/pricing'},
                    {text: '联系销售', href: '/contact'}
                ], '获取报价', '/contact'),
                createTemplateContainedSection([
                    {
                        type: 'row',
                        style: {
                            gap: '32px',
                            alignItems: 'center'
                        },
                        responsive: createTemplateResponsiveConfig({
                            gap: '24px'
                        }, {
                            gap: '18px'
                        }),
                        children: [
                            {
                                type: 'column',
                                props: {
                                    span: 5
                                },
                                responsive: createTemplateResponsiveConfig({}, {}, 12, 12),
                                blocks: [
                                    createTemplateHtmlNode('<div style="display:inline-flex;align-items:center;height:34px;padding:0 14px;border-radius:999px;background:#eff6ff;color:#2563eb;font-size:12px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;">Product Story Template</div>', {
                                        margin: '0 0 18px'
                                    }),
                                    createTemplateHeadingNode('h1', '把产品定位、核心功能和购买动作整理成一张完整详情页', {
                                        margin: '0 0 16px',
                                        fontSize: '52px',
                                        lineHeight: '1.18'
                                    }, {
                                        responsive: createTemplateResponsiveConfig({
                                            fontSize: '44px'
                                        }, {
                                            fontSize: '34px',
                                            lineHeight: '1.16'
                                        })
                                    }),
                                    createTemplateTextNode('适合展示产品定位、核心优势、应用场景、部署方式、案例证明和咨询入口。模板先帮你把结构搭好，后续也可切到模型详情驱动。', {
                                        margin: '0 0 24px',
                                        color: '#475569',
                                        lineHeight: '1.9',
                                        fontSize: '18px'
                                    }, {
                                        responsive: createTemplateResponsiveConfig({
                                            fontSize: '17px'
                                        }, {
                                            fontSize: '16px'
                                        })
                                    }),
                                    createTemplateButtonNode('立即咨询', '/contact', {
                                        align: 'left',
                                        variant: 'solid',
                                        style: {
                                            display: 'inline-flex',
                                            margin: '0 14px 14px 0',
                                            background: '#2563eb',
                                            color: '#ffffff',
                                            borderRadius: '14px',
                                            boxShadow: '0 16px 30px rgba(37,99,235,.22)',
                                            hoverBackground: '#1d4ed8'
                                        },
                                        responsive: createTemplateResponsiveConfig({}, {
                                            width: '100%',
                                            margin: '0 0 12px'
                                        })
                                    }),
                                    createTemplateButtonNode('预约产品演示', '/demo', {
                                        align: 'left',
                                        variant: 'outline',
                                        style: {
                                            display: 'inline-flex',
                                            borderRadius: '14px',
                                            color: '#0f172a',
                                            borderColor: '#cbd5e1',
                                            hoverBackground: '#ffffff'
                                        },
                                        responsive: createTemplateResponsiveConfig({}, {
                                            width: '100%'
                                        })
                                    }),
                                    createTemplateHtmlNode(
                                        '<div style="display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:14px;margin-top:18px;">' +
                                            '<div style="padding:16px 18px;border-radius:18px;background:#ffffff;border:1px solid #e2e8f0;box-shadow:0 12px 28px rgba(15,23,42,.06);"><div style="font-size:12px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#2563eb;">Deploy</div><div style="margin-top:8px;font-size:22px;font-weight:700;color:#0f172a;">SaaS / 私有化</div></div>' +
                                            '<div style="padding:16px 18px;border-radius:18px;background:#ffffff;border:1px solid #e2e8f0;box-shadow:0 12px 28px rgba(15,23,42,.06);"><div style="font-size:12px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#2563eb;">Support</div><div style="margin-top:8px;font-size:22px;font-weight:700;color:#0f172a;">1v1 导入支持</div></div>' +
                                        '</div>'
                                    )
                                ]
                            },
                            {
                                type: 'column',
                                props: {
                                    span: 7
                                },
                                responsive: createTemplateResponsiveConfig({}, {}, 12, 12),
                                blocks: [
                                    {
                                        type: 'image',
                                        props: {
                                            src: 'https://dummyimage.com/1280x900/e2e8f0/0f172a&text=Product+Hero',
                                            alt: '产品主图',
                                            align: 'center'
                                        },
                                        style: {
                                            width: '100%',
                                            borderRadius: '30px',
                                            minHeight: '400px',
                                            objectFit: 'cover',
                                            boxShadow: '0 28px 54px rgba(15,23,42,.12)'
                                        }
                                    }
                                ]
                            }
                        ]
                    }
                ], {
                    padding: '76px 0'
                }, '1180px', {
                    responsive: createTemplateResponsiveConfig({
                        padding: '68px 0'
                    }, {
                        padding: '56px 0'
                    })
                }),
                createTemplateStatsSection('产品详情页先回答这 4 个问题', '客户通常先看产品能解决什么、如何部署、多久上线、是否有案例可参考。', [
                    {label: '部署方式', value: 'SaaS', suffix: '/私有化', description: '按业务需要选择交付方式'},
                    {label: '上手周期', value: '1', suffix: '天', description: '导入模板后快速配置页面'},
                    {label: '核心功能', value: '模块化', suffix: '', description: '便于拆解产品价值'},
                    {label: '适配场景', value: '官网', suffix: '+营销页', description: '兼顾展示与转化承接'}
                ], {
                    background: '#f8fafc'
                }),
                createTemplateContainedSection([
                    createTemplateHeadingNode('h2', '把功能亮点拆成更容易理解的商业表达', {
                        textAlign: 'center',
                        margin: '0 0 14px',
                        fontSize: '38px'
                    }, {
                        responsive: createTemplateResponsiveConfig({
                            fontSize: '32px'
                        }, {
                            fontSize: '28px'
                        })
                    }),
                    createTemplateTextNode('对产品详情页来说，最重要的不是堆很多功能，而是让客户理解产品价值、部署方式和业务收益。', {
                        textAlign: 'center',
                        color: '#64748b',
                        maxWidth: '760px',
                        margin: '0 auto 30px',
                        lineHeight: '1.9'
                    }),
                    {
                        type: 'row',
                        style: {
                            gap: '20px'
                        },
                        responsive: createTemplateResponsiveConfig({
                            gap: '18px'
                        }, {
                            gap: '16px'
                        }),
                        children: [
                            createTemplateFeatureCardColumn(4, {
                                eyebrow: 'Feature',
                                title: '核心能力聚焦展示',
                                text: '先讲产品能解决的业务问题，再展开模块与功能细节。',
                                points: ['适合功能概览', '适合场景说明', '适合结果导向表达']
                            }),
                            createTemplateFeatureCardColumn(4, {
                                eyebrow: 'Deploy',
                                title: '部署与交付方式透明',
                                text: '清楚说明 SaaS、私有化、试用流程与实施支持，减少售前沟通成本。',
                                points: ['部署路径明确', '交付边界清晰', '支持追加价格区'],
                                background: '#f8fafc',
                                borderColor: '#e2e8f0',
                                badgeBackground: '#e2e8f0',
                                badgeColor: '#334155'
                            }),
                            createTemplateFeatureCardColumn(4, {
                                eyebrow: 'Proof',
                                title: '案例与截图强化可信度',
                                text: '用界面截图、案例成果和客户场景承接用户的决策需求。',
                                points: ['适合放界面图', '适合加客户案例', '便于补视频演示'],
                                background: '#0f172a',
                                borderColor: 'rgba(148,163,184,.22)',
                                badgeBackground: 'rgba(255,255,255,.12)',
                                badgeColor: '#ffffff',
                                titleColor: '#ffffff',
                                textColor: 'rgba(255,255,255,.82)',
                                boxShadow: '0 18px 42px rgba(15,23,42,.18)'
                            })
                        ]
                    }
                ], {
                    padding: '0 0 72px'
                }, '1180px'),
                createTemplateContainedSection([
                    {
                        type: 'gallery',
                        props: {
                            title: '产品细节展示',
                            subtitle: '适合展示界面截图、功能模块、应用场景或交付成果。',
                            source_type: 'manual',
                            columns: '3',
                            gap: '18px',
                            items: [
                                {title: '功能截图 01', image: 'https://dummyimage.com/960x720/dbeafe/1d4ed8&text=Feature+01', url: '#'},
                                {title: '功能截图 02', image: 'https://dummyimage.com/960x720/e2e8f0/0f172a&text=Feature+02', url: '#'},
                                {title: '功能截图 03', image: 'https://dummyimage.com/960x720/f8fafc/334155&text=Feature+03', url: '#'}
                            ]
                        }
                    }
                ], {
                    padding: '0 0 72px'
                }, '1180px'),
                createTemplateContainedSection([
                    {
                        type: 'row',
                        style: {
                            gap: '24px'
                        },
                        responsive: createTemplateResponsiveConfig({
                            gap: '20px'
                        }, {
                            gap: '16px'
                        }),
                        children: [
                            {
                                type: 'column',
                                props: {
                                    span: 7
                                },
                                responsive: createTemplateResponsiveConfig({}, {}, 12, 12),
                                blocks: [
                                    createTemplateHtmlNode(
                                        '<div style="padding:28px;border-radius:28px;background:#ffffff;border:1px solid #e2e8f0;box-shadow:0 18px 42px rgba(15,23,42,.08);">' +
                                            '<div style="font-size:12px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#2563eb;">Why It Works</div>' +
                                            '<h3 style="margin:14px 0 12px;font-size:30px;line-height:1.25;color:#0f172a;">把产品收益写成客户能直接判断的结果</h3>' +
                                            '<p style="margin:0;color:#64748b;line-height:1.9;">这里适合放产品优势对比、ROI 说明、上线周期、实施方式和售后支持。相比单纯罗列功能，更有利于售前沟通。</p>' +
                                        '</div>'
                                    )
                                ]
                            },
                            {
                                type: 'column',
                                props: {
                                    span: 5
                                },
                                responsive: createTemplateResponsiveConfig({}, {}, 12, 12),
                                blocks: [
                                    createTemplateHtmlNode(
                                        '<div style="padding:28px;border-radius:28px;background:linear-gradient(160deg,#0f172a 0%,#1e293b 100%);color:#ffffff;box-shadow:0 24px 48px rgba(15,23,42,.18);">' +
                                            '<div style="font-size:13px;opacity:.72;">Recommended CTA</div>' +
                                            '<div style="margin-top:10px;font-size:28px;font-weight:700;line-height:1.25;">适合在这里加入报价、试用或演示申请</div>' +
                                            '<div style="margin-top:14px;color:rgba(255,255,255,.76);line-height:1.85;">把最关键的购买动作留在功能与截图之后，通常更容易承接高意向线索。</div>' +
                                        '</div>'
                                    )
                                ]
                            }
                        ]
                    }
                ], {
                    padding: '0 0 72px'
                }, '1180px'),
                createTemplateFaqSection('产品详情页 FAQ', '把售前最常问的部署、价格、试用和交付方式统一放在详情页尾部。', [
                    {question: '产品详情页后面能接模型详情吗？', answer: '可以，这套模板先给固定结构，后续可以切到 model_detail 读取真实详情数据。'},
                    {question: '可以放产品视频吗？', answer: '可以，当前编辑器已经支持视频组件，适合放演示视频或讲解视频。'},
                    {question: '按钮样式能继续改吗？', answer: '可以，按钮现在支持对齐、边框按钮、最小高度和悬浮态。'},
                    {question: '图库能改成模型来源吗？', answer: '可以，图库组件支持手动项和模型列表来源两种模式。'}
                ]),
                createTemplateCtaSection({
                    eyebrow: 'Product Detail',
                    title: '详情页结构已经搭好，重点替换产品信息和购买动作',
                    description: '先替换主图、标题、卖点、部署方式和购买动作，再根据业务决定是否切到模型详情驱动。',
                    primaryText: '获取产品报价',
                    primaryHref: '/contact',
                    secondaryText: '预约产品演示',
                    secondaryHref: '/demo',
                    align: 'left'
                })
            ];
        }

        function createArticleListTemplateSections() {
            return [
                createTemplateNavigationSection('内容中心', [
                    {text: '首页', href: '/'},
                    {text: '资讯列表', href: '/news'},
                    {text: '行业观察', href: '/news/insights'},
                    {text: '产品动态', href: '/news/product'},
                    {text: '联系我们', href: '/contact'}
                ], '订阅更新', '/contact'),
                createTemplateContainedSection([
                    {
                        type: 'row',
                        style: {
                            gap: '24px',
                            alignItems: 'center'
                        },
                        responsive: createTemplateResponsiveConfig({
                            gap: '20px'
                        }, {
                            gap: '18px'
                        }),
                        children: [
                            {
                                type: 'column',
                                props: {
                                    span: 7
                                },
                                responsive: createTemplateResponsiveConfig({}, {}, 12, 12),
                                blocks: [
                                    createTemplateHtmlNode('<div style="display:inline-flex;align-items:center;height:34px;padding:0 14px;border-radius:999px;background:#eff6ff;color:#2563eb;font-size:12px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;">Editorial Hub Template</div>', {
                                        margin: '0 0 18px'
                                    }),
                                    createTemplateHeadingNode('h1', '把资讯、观察和品牌内容整合成更成熟的内容中心首页', {
                                        margin: '0 0 16px',
                                        fontSize: '52px',
                                        lineHeight: '1.14'
                                    }, {
                                        responsive: createTemplateResponsiveConfig({
                                            fontSize: '44px'
                                        }, {
                                            fontSize: '34px',
                                            lineHeight: '1.18'
                                        })
                                    }),
                                    createTemplateTextNode('适合新闻资讯、行业观察、知识文章、品牌动态和更新日志等内容列表页。模板会先把内容入口、列表区和底部转化区搭好，后续可直接接模型列表。', {
                                        color: '#64748b',
                                        maxWidth: '760px',
                                        lineHeight: '1.9'
                                    })
                                ]
                            },
                            {
                                type: 'column',
                                props: {
                                    span: 5
                                },
                                responsive: createTemplateResponsiveConfig({}, {}, 12, 12),
                                blocks: [
                                    createTemplateHtmlNode(
                                        '<div style="padding:28px;border-radius:28px;background:#ffffff;border:1px solid #e2e8f0;box-shadow:0 18px 42px rgba(15,23,42,.08);">' +
                                            '<div style="display:flex;justify-content:space-between;align-items:center;gap:12px;margin-bottom:16px;"><div style="font-size:14px;font-weight:700;color:#0f172a;">内容策略看板</div><div style="padding:6px 10px;border-radius:999px;background:#eff6ff;color:#2563eb;font-size:12px;font-weight:700;">可持续运营</div></div>' +
                                            '<div style="display:grid;gap:14px;">' +
                                                '<div style="padding:16px;border-radius:18px;background:#f8fafc;"><div style="font-size:12px;color:#64748b;">栏目规划</div><div style="margin-top:6px;font-size:22px;font-weight:700;color:#0f172a;">资讯 / 洞察 / 动态</div></div>' +
                                                '<div style="padding:16px;border-radius:18px;background:#f8fafc;"><div style="font-size:12px;color:#64748b;">推荐动作</div><div style="margin-top:6px;font-size:22px;font-weight:700;color:#0f172a;">订阅、留资、跳详情</div></div>' +
                                            '</div>' +
                                        '</div>'
                                    )
                                ]
                            }
                        ]
                    }
                ], {
                    padding: '82px 0 56px',
                    background: 'linear-gradient(135deg, #eff6ff 0%, #ffffff 100%)'
                }, '1180px'),
                createTemplateContainedSection([
                    {
                        type: 'row',
                        style: {
                            gap: '20px'
                        },
                        responsive: createTemplateResponsiveConfig({
                            gap: '18px'
                        }, {
                            gap: '16px'
                        }),
                        children: [
                            createTemplateFeatureCardColumn(4, {
                                eyebrow: 'Column',
                                title: '用栏目结构提升可读性',
                                text: '先把资讯、洞察、品牌动态分成稳定栏目，方便后续做持续更新。',
                                points: ['栏目语义更清晰', '利于运营协作', '利于后续做筛选']
                            }),
                            createTemplateFeatureCardColumn(4, {
                                eyebrow: 'Traffic',
                                title: '把流量引向高价值内容',
                                text: '列表页不仅展示文章，更适合作为详情页、表单和订阅入口的中枢。',
                                points: ['卡片摘要更易点击', '支持模型来源', '适合补热门推荐'],
                                background: '#f8fafc',
                                borderColor: '#e2e8f0',
                                badgeBackground: '#e2e8f0',
                                badgeColor: '#334155'
                            }),
                            createTemplateFeatureCardColumn(4, {
                                eyebrow: 'Convert',
                                title: '内容页也保留转化动作',
                                text: '在列表区底部保留订阅、咨询或资料领取动作，让内容与线索增长联动。',
                                points: ['适合资料下载', '适合邮件订阅', '适合咨询承接'],
                                background: '#0f172a',
                                borderColor: 'rgba(148,163,184,.22)',
                                badgeBackground: 'rgba(255,255,255,.12)',
                                badgeColor: '#ffffff',
                                titleColor: '#ffffff',
                                textColor: 'rgba(255,255,255,.82)',
                                boxShadow: '0 18px 42px rgba(15,23,42,.18)'
                            })
                        ]
                    }
                ], {
                    padding: '0 0 56px'
                }, '1180px'),
                createTemplateContainedSection([
                    {
                        type: 'model_list',
                        props: {
                            title: '最新资讯',
                            template: 'card',
                            model: 'article',
                            limit: '9',
                            order_by: 'published_at',
                            order_direction: 'desc',
                            title_field: 'title',
                            summary_field: 'summary',
                            image_field: 'cover',
                            date_field: 'published_at',
                            url_field: 'url',
                            detail_prefix: '/news/'
                        }
                    }
                ], {
                    padding: '0 0 72px'
                }, '1180px'),
                createTemplateCtaSection({
                    eyebrow: 'Article List',
                    title: '列表页先接内容模型，再逐步补 SEO 与筛选能力',
                    description: '这套模板已经把内容入口、模型列表和底部转化区搭好，后续继续补分类筛选、详情跳转或订阅表单即可。',
                    primaryText: '继续完善内容模型',
                    primaryHref: '/contact',
                    secondaryText: '查看详情页模板',
                    secondaryHref: '/news/example',
                    align: 'center',
                    actionsAlign: 'center'
                })
            ];
        }

        function createContactConvertTemplateSections() {
            return [
                createTemplateNavigationSection('联系与咨询', [
                    {text: '页首', href: '#hero'},
                    {text: '联系信息', href: '#contact-info'},
                    {text: '提交需求', href: '#contact-form'},
                    {text: '常见问题', href: '#contact-faq'}
                ], '立即提交需求', '#contact-form'),
                {
                    type: 'sidebar',
                    props: {
                        title: '快捷入口',
                        position: 'right',
                        offsetTop: '132px',
                        showBackTop: '1',
                        items: [
                            {text: '联系信息', href: '#contact-info', icon: '联'},
                            {text: '提交需求', href: '#contact-form', icon: '单'},
                            {text: '常见问题', href: '#contact-faq', icon: '问'}
                        ]
                    }
                },
                withNodeAnchor(createTemplateContainedSection([
                    {
                        type: 'row',
                        style: {
                            gap: '24px',
                            alignItems: 'center'
                        },
                        responsive: createTemplateResponsiveConfig({
                            gap: '20px'
                        }, {
                            gap: '18px'
                        }),
                        children: [
                            {
                                type: 'column',
                                props: {
                                    span: 7
                                },
                                responsive: createTemplateResponsiveConfig({}, {}, 12, 12),
                                blocks: [
                                    createTemplateHtmlNode('<div style="display:inline-flex;align-items:center;height:34px;padding:0 14px;border-radius:999px;background:#eff6ff;color:#2563eb;font-size:12px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;">Lead Capture Contact Page</div>', {
                                        margin: '0 0 18px'
                                    }),
                                    createTemplateHeadingNode('h1', '把联系方式、顾问沟通和线索提交整合成一张真正能转化的联系页', {
                                        margin: '0 0 16px',
                                        fontSize: '50px',
                                        lineHeight: '1.14'
                                    }, {
                                        responsive: createTemplateResponsiveConfig({
                                            fontSize: '42px'
                                        }, {
                                            fontSize: '34px',
                                            lineHeight: '1.18'
                                        })
                                    }),
                                    createTemplateTextNode('适合放联系方式、顾问二维码、咨询表单、合作说明和 FAQ。相比只有电话和地址的联系页，这一版更强调响应承诺与线索承接。', {
                                        color: '#64748b',
                                        maxWidth: '760px',
                                        lineHeight: '1.9'
                                    })
                                ]
                            },
                            {
                                type: 'column',
                                props: {
                                    span: 5
                                },
                                responsive: createTemplateResponsiveConfig({}, {}, 12, 12),
                                blocks: [
                                    createTemplateHtmlNode(
                                        '<div style="padding:28px;border-radius:28px;background:linear-gradient(160deg,#0f172a 0%,#1e293b 100%);color:#ffffff;box-shadow:0 24px 48px rgba(15,23,42,.18);">' +
                                            '<div style="font-size:13px;opacity:.72;">Response Promise</div>' +
                                            '<div style="margin-top:10px;font-size:30px;font-weight:700;line-height:1.2;">工作日 30 分钟内响应</div>' +
                                            '<div style="margin-top:14px;color:rgba(255,255,255,.78);line-height:1.85;">这一块建议替换成你的回访时效、顾问机制、服务范围和合作说明，能显著提升咨询意愿。</div>' +
                                            '<div style="display:grid;gap:12px;margin-top:18px;">' +
                                                '<div style="padding:14px 16px;border-radius:16px;background:rgba(255,255,255,.10);">支持电话、微信、邮箱与表单同步承接</div>' +
                                                '<div style="padding:14px 16px;border-radius:16px;background:rgba(255,255,255,.10);">适合商务咨询、项目合作、预约演示</div>' +
                                            '</div>' +
                                        '</div>'
                                    )
                                ]
                            }
                        ]
                    }
                ], {
                    padding: '82px 0 48px',
                    background: 'linear-gradient(135deg, #eff6ff 0%, #ffffff 100%)'
                }, '1180px'), 'hero'),
                withNodeAnchor(createTemplateContainedSection([
                    {
                        type: 'row',
                        style: {
                            gap: '20px'
                        },
                        responsive: createTemplateResponsiveConfig({
                            gap: '18px'
                        }, {
                            gap: '16px'
                        }),
                        children: [
                            createTemplateFeatureCardColumn(4, {
                                eyebrow: 'Phone',
                                title: '电话与工作时间',
                                text: '写清固定电话、商务手机、服务时间和可约回访时段。',
                                points: ['减少无效沟通', '便于销售跟进', '建议写明响应时间']
                            }),
                            createTemplateFeatureCardColumn(4, {
                                eyebrow: 'Mail',
                                title: '邮箱与合作范围',
                                text: '说明商务邮箱、合作方向、项目类型和对接流程，方便客户快速判断。',
                                points: ['适合商务合作', '适合媒体沟通', '适合投标与采购'],
                                background: '#f8fafc',
                                borderColor: '#e2e8f0',
                                badgeBackground: '#e2e8f0',
                                badgeColor: '#334155'
                            }),
                            createTemplateFeatureCardColumn(4, {
                                eyebrow: 'WeChat',
                                title: '微信或顾问二维码',
                                text: '高意向用户更倾向于直接联系顾问，这一块建议保留在首屏下方。',
                                points: ['适合企业微信', '适合销售顾问', '适合演示预约'],
                                background: '#0f172a',
                                borderColor: 'rgba(148,163,184,.22)',
                                badgeBackground: 'rgba(255,255,255,.12)',
                                badgeColor: '#ffffff',
                                titleColor: '#ffffff',
                                textColor: 'rgba(255,255,255,.82)',
                                boxShadow: '0 18px 42px rgba(15,23,42,.18)'
                            })
                        ]
                    }
                ], {
                    padding: '0 0 56px'
                }, '1180px'), 'contact-info'),
                withNodeAnchor(createTemplateContainedSection([
                    {
                        type: 'row',
                        style: {
                            gap: '24px'
                        },
                        responsive: createTemplateResponsiveConfig({
                            gap: '20px'
                        }, {
                            gap: '18px'
                        }),
                        children: [
                            {
                                type: 'column',
                                props: {
                                    span: 7
                                },
                                responsive: createTemplateResponsiveConfig({}, {}, 12, 12),
                                blocks: [
                                    createTemplateHtmlNode(
                                        '<div style="padding:28px;border-radius:28px;background:#ffffff;border:1px solid #e2e8f0;box-shadow:0 18px 42px rgba(15,23,42,.08);">' +
                                            '<div style="font-size:12px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#2563eb;">Business Contact</div>' +
                                            '<h3 style="margin:14px 0 12px;font-size:30px;line-height:1.25;color:#0f172a;">把地址、电话、邮箱和合作说明放在同一区域</h3>' +
                                            '<p style="margin:0;color:#64748b;line-height:1.9;">建议在这里放办公地址、顾问时间、合作流程、回访机制和服务范围，减少来回沟通成本。</p>' +
                                        '</div>'
                                    ),
                                    createTemplateTextNode('建议把电话、企业微信、邮箱、办公地址和工作时间一起写清楚，减少来回沟通成本。', {
                                        margin: '18px 0 0',
                                        color: '#64748b',
                                        lineHeight: '1.85'
                                    })
                                ]
                            },
                            {
                                type: 'column',
                                props: {
                                    span: 5
                                },
                                responsive: createTemplateResponsiveConfig({}, {}, 12, 12),
                                blocks: [
                                    {
                                        type: 'qrcode',
                                        props: {
                                            title: '顾问二维码',
                                            text: '扫码添加顾问，获取方案与报价',
                                            value: 'https://example.com/contact',
                                            size: '180'
                                        }
                                    }
                                ]
                            }
                        ]
                    }
                ], {
                    padding: '0 0 56px'
                }, '1180px'), 'contact-qrcode'),
                withNodeAnchor(createTemplateLeadFormSection('把联系页直接做成转化页', '除了展示联系方式，更重要的是让用户能在当前页直接提交需求、预约回访或申请演示。'), 'contact-form'),
                withNodeAnchor(createTemplateFaqSection('联系咨询前常见问题', '把响应方式、回访时间、合作流程和交付方式提前说明。', [
                    {question: '提交后多久会有人联系？', answer: '建议在这里写明工作日的回访时效，比如 30 分钟内响应、当天联系等。'},
                    {question: '联系页适合放哪些信息？', answer: '建议放联系方式、顾问二维码、表单、办公时间、服务范围与 FAQ。'},
                    {question: '这个页面能加地图吗？', answer: '可以，后续可以继续插入自定义 HTML 或图文模块，补办公地点与交通信息。'},
                    {question: '侧边快捷栏和返回顶部能继续改吗？', answer: '可以，当前编辑器已经支持侧边栏项目、页内锚点和返回顶部的可视化配置。'}
                ]), 'contact-faq'),
                createTemplateCtaSection({
                    eyebrow: 'Contact Page',
                    title: '联系入口和表单都已经准备好了',
                    description: '下一步重点替换联系人信息、二维码地址、表单字段和回访承诺，让这张页面真正能稳定承接线索。',
                    primaryText: '开始替换联系信息',
                    primaryHref: '/contact',
                    secondaryText: '查看服务页模板',
                    secondaryHref: '/services',
                    align: 'left'
                })
            ];
        }

        function createLayoutPresetMap() {
            return {
                hero: [
                    {
                        type: 'section',
                        props: {
                            contentWidth: 'contained',
                            innerWidth: '1180px'
                        },
                        style: {
                            padding: '88px 0',
                            background: 'linear-gradient(135deg, #eff6ff 0%, #ffffff 100%)'
                        },
                        children: [
                            {
                                type: 'heading',
                                props: {
                                    level: 'h1',
                                    text: '这是一个 Hero 首屏标题'
                                },
                                style: {
                                    textAlign: 'center',
                                    fontSize: '52px',
                                    color: '#0f172a',
                                    margin: '0 0 20px'
                                }
                            },
                            {
                                type: 'text',
                                props: {
                                    text: '这里适合放一句页面主文案，强调品牌价值、核心卖点或活动说明。'
                                },
                                style: {
                                    textAlign: 'center',
                                    fontSize: '18px',
                                    lineHeight: '1.9',
                                    color: '#475569',
                                    maxWidth: '760px',
                                    margin: '0 auto 28px'
                                }
                            },
                            {
                                type: 'button',
                                props: {
                                    text: '立即开始',
                                    href: '#'
                                },
                                style: {
                                    display: 'inline-flex',
                                    margin: '0 auto',
                                    background: '#2563eb',
                                    color: '#ffffff',
                                    padding: '14px 28px'
                                }
                            }
                        ]
                    }
                ],
                'two-column': [
                    {
                        type: 'section',
                        props: {
                            contentWidth: 'contained',
                            innerWidth: '1180px'
                        },
                        style: {
                            padding: '56px 0'
                        },
                        children: [
                            {
                                type: 'row',
                                style: {
                                    gap: '24px'
                                },
                                children: [
                                    {
                                        type: 'column',
                                        props: {
                                            span: 6
                                        },
                                        blocks: [
                                            {
                                                type: 'heading',
                                                props: {
                                                    level: 'h2',
                                                    text: '左侧内容'
                                                },
                                                style: {
                                                    margin: '0 0 16px'
                                                }
                                            },
                                            {
                                                type: 'text',
                                                props: {
                                                    text: '这里可以放图文说明、服务介绍或模块摘要。'
                                                }
                                            }
                                        ]
                                    },
                                    {
                                        type: 'column',
                                        props: {
                                            span: 6
                                        },
                                        blocks: [
                                            {
                                                type: 'heading',
                                                props: {
                                                    level: 'h2',
                                                    text: '右侧内容'
                                                },
                                                style: {
                                                    margin: '0 0 16px'
                                                }
                                            },
                                            {
                                                type: 'text',
                                                props: {
                                                    text: '这里适合放第二组信息、卡片、表单说明或补充文案。'
                                                }
                                            }
                                        ]
                                    }
                                ]
                            }
                        ]
                    }
                ],
                'three-column': [
                    {
                        type: 'section',
                        props: {
                            contentWidth: 'contained',
                            innerWidth: '1180px'
                        },
                        style: {
                            padding: '56px 0'
                        },
                        children: [
                            {
                                type: 'row',
                                style: {
                                    gap: '20px'
                                },
                                children: [1, 2, 3].map(function (index) {
                                    return {
                                        type: 'column',
                                        props: {
                                            span: 4
                                        },
                                        blocks: [
                                            {
                                                type: 'heading',
                                                props: {
                                                    level: 'h3',
                                                    text: '模块 ' + index
                                                },
                                                style: {
                                                    margin: '0 0 12px'
                                                }
                                            },
                                            {
                                                type: 'text',
                                                props: {
                                                    text: '这里是第 ' + index + ' 个三列内容区，可以继续换成图片、按钮或模型组件。'
                                                }
                                            }
                                        ]
                                    };
                                })
                            }
                        ]
                    }
                ],
                'image-text': [
                    {
                        type: 'section',
                        props: {
                            contentWidth: 'contained',
                            innerWidth: '1180px'
                        },
                        style: {
                            padding: '64px 0'
                        },
                        children: [
                            {
                                type: 'row',
                                style: {
                                    gap: '28px',
                                    alignItems: 'center'
                                },
                                children: [
                                    {
                                        type: 'column',
                                        props: {
                                            span: 6
                                        },
                                        blocks: [
                                            {
                                                type: 'image',
                                                props: {
                                                    src: 'https://dummyimage.com/900x640/e2e8f0/64748b&text=Image',
                                                    alt: '示例图片'
                                                },
                                                style: {
                                                    width: '100%',
                                                    borderRadius: '20px',
                                                    objectFit: 'cover'
                                                }
                                            }
                                        ]
                                    },
                                    {
                                        type: 'column',
                                        props: {
                                            span: 6
                                        },
                                        blocks: [
                                            {
                                                type: 'heading',
                                                props: {
                                                    level: 'h2',
                                                    text: '左图右文模板'
                                                },
                                                style: {
                                                    margin: '0 0 16px'
                                                }
                                            },
                                            {
                                                type: 'text',
                                                props: {
                                                    text: '这是一个常见的介绍型布局，适合品牌介绍、产品功能、服务说明、案例展示等场景。'
                                                },
                                                style: {
                                                    margin: '0 0 24px'
                                                }
                                            },
                                            {
                                                type: 'button',
                                                props: {
                                                    text: '查看详情',
                                                    href: '#'
                                                }
                                            }
                                        ]
                                    }
                                ]
                            }
                        ]
                    }
                ],
                'feature-cards': [
                    {
                        type: 'section',
                        props: {
                            contentWidth: 'contained',
                            innerWidth: '1180px'
                        },
                        style: {
                            padding: '64px 0',
                            background: '#f8fafc'
                        },
                        children: [
                            {
                                type: 'heading',
                                props: {
                                    level: 'h2',
                                    text: '核心能力'
                                },
                                style: {
                                    textAlign: 'center',
                                    margin: '0 0 14px'
                                }
                            },
                            {
                                type: 'text',
                                props: {
                                    text: '适合产品功能、服务卖点、方案亮点等三卡展示场景。'
                                },
                                style: {
                                    textAlign: 'center',
                                    color: '#64748b',
                                    margin: '0 0 30px'
                                }
                            },
                            {
                                type: 'row',
                                style: {
                                    gap: '20px'
                                },
                                children: [1, 2, 3].map(function (index) {
                                    return {
                                        type: 'column',
                                        props: {
                                            span: 4
                                        },
                                        blocks: [
                                            {
                                                type: 'html',
                                                props: {
                                                    html: '<div style="padding:28px;border-radius:20px;background:#fff;border:1px solid #e2e8f0;box-shadow:0 12px 28px rgba(15,23,42,.05);"><h3 style="margin:0 0 12px;font-size:20px;color:#0f172a;">能力 ' + index + '</h3><p style="margin:0;color:#64748b;line-height:1.8;">这里可以放这一项能力的简短描述、价值点或功能说明。</p></div>'
                                                }
                                            }
                                        ]
                                    };
                                })
                            }
                        ]
                    }
                ],
                faq: [
                    {
                        type: 'section',
                        props: {
                            contentWidth: 'contained',
                            innerWidth: '980px'
                        },
                        style: {
                            padding: '64px 0'
                        },
                        children: [
                            {
                                type: 'heading',
                                props: {
                                    level: 'h2',
                                    text: '常见问题'
                                },
                                style: {
                                    textAlign: 'center',
                                    margin: '0 0 28px'
                                }
                            },
                            {
                                type: 'html',
                                props: {
                                    html: '<div style="display:grid;gap:16px;"><div style="padding:22px 24px;border-radius:16px;border:1px solid #e2e8f0;background:#fff;"><h3 style="margin:0 0 10px;font-size:18px;color:#0f172a;">Q1：这里适合放什么？</h3><p style="margin:0;color:#64748b;line-height:1.8;">适合放页面常见问题、购买须知、服务说明、交付流程、售后规则等内容。</p></div><div style="padding:22px 24px;border-radius:16px;border:1px solid #e2e8f0;background:#fff;"><h3 style="margin:0 0 10px;font-size:18px;color:#0f172a;">Q2：后面还能继续改吗？</h3><p style="margin:0;color:#64748b;line-height:1.8;">可以，这个模板只是起步结构，插入后仍然可以继续拖拽、拆分、替换和细调。</p></div></div>'
                                }
                            }
                        ]
                    }
                ],
                'cta-band': [
                    {
                        type: 'section',
                        style: {
                            padding: '72px 0',
                            background: 'linear-gradient(135deg, #1d4ed8 0%, #2563eb 100%)'
                        },
                        children: [
                            {
                                type: 'heading',
                                props: {
                                    level: 'h2',
                                    text: '准备开始你的下一张页面了吗？'
                                },
                                style: {
                                    textAlign: 'center',
                                    color: '#ffffff',
                                    margin: '0 0 16px'
                                }
                            },
                            {
                                type: 'text',
                                props: {
                                    text: '这是一个适合放在页面底部的转化区块，可以承接咨询、注册、购买、联系等动作。'
                                },
                                style: {
                                    textAlign: 'center',
                                    color: 'rgba(255,255,255,.88)',
                                    maxWidth: '760px',
                                    margin: '0 auto 26px'
                                }
                            },
                            {
                                type: 'button',
                                props: {
                                    text: '立即咨询',
                                    href: '#'
                                },
                                style: {
                                    display: 'inline-flex',
                                    margin: '0 auto',
                                    background: '#ffffff',
                                    color: '#1d4ed8',
                                    padding: '14px 28px',
                                    borderRadius: '999px'
                                }
                            }
                        ]
                    }
                ],
                'asymmetric-columns': [
                    {
                        type: 'section',
                        props: {
                            contentWidth: 'contained',
                            innerWidth: '1180px'
                        },
                        style: {
                            padding: '60px 0'
                        },
                        children: [
                            {
                                type: 'row',
                                style: {
                                    gap: '24px'
                                },
                                children: [
                                    {
                                        type: 'column',
                                        props: {
                                            span: 4
                                        },
                                        blocks: createDefaultColumnBlocks('左侧侧栏', '这里适合放导航、摘要、标签或辅助说明。')
                                    },
                                    {
                                        type: 'column',
                                        props: {
                                            span: 8
                                        },
                                        blocks: createDefaultColumnBlocks('右侧主内容', '这里适合放正文、图文说明、表单或卡片矩阵。')
                                    }
                                ]
                            }
                        ]
                    }
                ],
                'four-column': [
                    {
                        type: 'section',
                        props: {
                            contentWidth: 'contained',
                            innerWidth: '1180px'
                        },
                        style: {
                            padding: '60px 0',
                            background: '#f8fafc'
                        },
                        children: [
                            {
                                type: 'heading',
                                props: {
                                    level: 'h2',
                                    text: '四列矩阵'
                                },
                                style: {
                                    textAlign: 'center',
                                    margin: '0 0 24px'
                                }
                            },
                            createRowLayoutNode([3, 3, 3, 3])
                        ]
                    }
                ],
                'lead-form': [
                    {
                        type: 'section',
                        props: {
                            contentWidth: 'contained',
                            innerWidth: '1180px'
                        },
                        style: {
                            padding: '64px 0',
                            background: 'linear-gradient(135deg, #eff6ff 0%, #ffffff 100%)'
                        },
                        children: [
                            {
                                type: 'row',
                                style: {
                                    gap: '28px',
                                    alignItems: 'center'
                                },
                                children: [
                                    {
                                        type: 'column',
                                        props: {
                                            span: 7
                                        },
                                        blocks: [
                                            {
                                                type: 'heading',
                                                props: {
                                                    level: 'h2',
                                                    text: '留下线索，快速获得方案'
                                                },
                                                style: {
                                                    margin: '0 0 14px'
                                                }
                                            },
                                            {
                                                type: 'text',
                                                props: {
                                                    text: '适合咨询、预约、报名、合作申请等场景，左边放说明，右边直接承接表单转化。'
                                                },
                                                style: {
                                                    color: '#64748b',
                                                    lineHeight: '1.9'
                                                }
                                            }
                                        ]
                                    },
                                    {
                                        type: 'column',
                                        props: {
                                            span: 5
                                        },
                                        blocks: createContainerQuickNodes('column-form')
                                    }
                                ]
                            }
                        ]
                    }
                ],
                'page-corp-home': createCorporateHomeTemplateSections(),
                'page-service-landing': createServiceLandingTemplateSections(),
                'page-product-detail': createProductDetailTemplateSections(),
                'page-article-list': createArticleListTemplateSections(),
                'page-contact-convert': createContactConvertTemplateSections()
            };
        }

        function syncSchemaTextarea() {
            layoutSchema.value = JSON.stringify(builderState, null, 4);
        }

        function getValueByPath(root, path) {
            if (!path) {
                return root;
            }
            return path.split('.').reduce(function (current, segment) {
                if (current == null) {
                    return null;
                }
                if (/^\d+$/.test(segment)) {
                    return current[parseInt(segment, 10)];
                }
                return current[segment];
            }, root);
        }

        function getNodeByPath(path) {
            return getValueByPath(builderState, path);
        }

        function findNodePathById(targetId, root, basePath) {
            if (!targetId) {
                return '';
            }
            root = root || builderState;
            basePath = basePath || '';
            if (Array.isArray(root)) {
                for (var i = 0; i < root.length; i += 1) {
                    var path = findNodePathById(targetId, root[i], basePath ? (basePath + '.' + i) : String(i));
                    if (path) {
                        return path;
                    }
                }
                return '';
            }
            if (!root || typeof root !== 'object') {
                return '';
            }
            if (root.id && String(root.id) === String(targetId)) {
                return basePath;
            }
            var childKey = getChildCollectionKey(root);
            if (childKey && Array.isArray(root[childKey])) {
                for (var index = 0; index < root[childKey].length; index += 1) {
                    var childPath = findNodePathById(targetId, root[childKey][index], (basePath ? (basePath + '.') : '') + childKey + '.' + index);
                    if (childPath) {
                        return childPath;
                    }
                }
            }
            return '';
        }

        function getCollectionByPath(path) {
            var collection = getValueByPath(builderState, path);
            return Array.isArray(collection) ? collection : null;
        }

        function getParentCollectionPath(path) {
            var segments = path.split('.');
            segments.pop();
            return segments.join('.');
        }

        function getPathIndex(path) {
            var segments = path.split('.');
            return parseInt(segments[segments.length - 1], 10);
        }

        function isSameOrChildPath(sourcePath, targetPath) {
            return !!sourcePath && !!targetPath && (sourcePath === targetPath || targetPath.indexOf(sourcePath + '.') === 0);
        }

        function adjustSiblingPathAfterRemoval(path, removedPath) {
            if (!path) {
                return path;
            }
            if (path === removedPath || path.indexOf(removedPath + '.') === 0) {
                return '';
            }
            var parentPath = getParentCollectionPath(path);
            var removedParentPath = getParentCollectionPath(removedPath);
            if (parentPath !== removedParentPath) {
                return path;
            }
            var currentIndex = getPathIndex(path);
            var removedIndex = getPathIndex(removedPath);
            if (currentIndex > removedIndex) {
                return parentPath + '.' + (currentIndex - 1);
            }
            return path;
        }

        function isContainerNode(node) {
            return !!node && ['section', 'row', 'column'].indexOf(node.type) !== -1;
        }

        function setNodeNestedValue(node, target, key, value) {
            if (!node[target] || typeof node[target] !== 'object') {
                node[target] = {};
            }
            if (value === null || value === '') {
                delete node[target][key];
                if (!Object.keys(node[target]).length) {
                    node[target] = {};
                }
                return;
            }
            node[target][key] = value;
        }

        function ensureResponsiveConfig(node, device) {
            if (!node.responsive || typeof node.responsive !== 'object') {
                node.responsive = {};
            }
            if (!node.responsive[device] || typeof node.responsive[device] !== 'object') {
                node.responsive[device] = {};
            }
            if (!node.responsive[device].style || typeof node.responsive[device].style !== 'object') {
                node.responsive[device].style = {};
            }
            return node.responsive[device];
        }

        function cleanupResponsiveConfig(node) {
            if (!node || !node.responsive || typeof node.responsive !== 'object') {
                return;
            }
            ['tablet', 'mobile'].forEach(function (device) {
                var config = node.responsive[device];
                if (!config || typeof config !== 'object') {
                    delete node.responsive[device];
                    return;
                }
                if (!config.style || typeof config.style !== 'object') {
                    config.style = {};
                }
                if (!Object.keys(config.style).length) {
                    delete config.style;
                }
                if (!(parseInt(config.span, 10) >= 1 && parseInt(config.span, 10) <= 12)) {
                    delete config.span;
                } else {
                    config.span = parseInt(config.span, 10);
                }
                if (!Object.keys(config).length) {
                    delete node.responsive[device];
                }
            });
            if (!Object.keys(node.responsive).length) {
                delete node.responsive;
            }
        }

        function getResponsiveStyle(node, device) {
            if (device === 'desktop' || !node || !node.responsive || !node.responsive[device] || !node.responsive[device].style) {
                return {};
            }
            return node.responsive[device].style;
        }

        function fillResponsiveVisualFields(device, style) {
            style = style && typeof style === 'object' ? style : {};
            if (device === 'tablet') {
                if (builderTabletPadding) {
                    builderTabletPadding.value = style.padding || '';
                }
                if (builderTabletMargin) {
                    builderTabletMargin.value = style.margin || '';
                }
                if (builderTabletFontSize) {
                    builderTabletFontSize.value = style.fontSize || '';
                }
                if (builderTabletGap) {
                    builderTabletGap.value = style.gap || '';
                }
                return;
            }
            if (builderMobilePadding) {
                builderMobilePadding.value = style.padding || '';
            }
            if (builderMobileMargin) {
                builderMobileMargin.value = style.margin || '';
            }
            if (builderMobileFontSize) {
                builderMobileFontSize.value = style.fontSize || '';
            }
            if (builderMobileGap) {
                builderMobileGap.value = style.gap || '';
            }
        }

        function applyResponsiveVisualConfig(device) {
            var node = getNodeByPath(selectedPath);
            if (!node || (device !== 'tablet' && device !== 'mobile')) {
                return;
            }
            var config = ensureResponsiveConfig(node, device);
            setNodeNestedValue(config, 'style', 'padding', device === 'tablet' && builderTabletPadding ? builderTabletPadding.value.trim() : device === 'mobile' && builderMobilePadding ? builderMobilePadding.value.trim() : '');
            setNodeNestedValue(config, 'style', 'margin', device === 'tablet' && builderTabletMargin ? builderTabletMargin.value.trim() : device === 'mobile' && builderMobileMargin ? builderMobileMargin.value.trim() : '');
            setNodeNestedValue(config, 'style', 'fontSize', device === 'tablet' && builderTabletFontSize ? builderTabletFontSize.value.trim() : device === 'mobile' && builderMobileFontSize ? builderMobileFontSize.value.trim() : '');
            setNodeNestedValue(config, 'style', 'gap', device === 'tablet' && builderTabletGap ? builderTabletGap.value.trim() : device === 'mobile' && builderMobileGap ? builderMobileGap.value.trim() : '');
            cleanupResponsiveConfig(node);
            refreshBuilder();
        }

        function getMergedNodeStyle(node, device) {
            var baseStyle = node && node.style && typeof node.style === 'object' ? node.style : {};
            if (device === 'desktop') {
                return baseStyle;
            }
            var responsiveStyle = getResponsiveStyle(node, device);
            return Object.assign({}, baseStyle, responsiveStyle);
        }

        function getEffectiveColumnSpan(node, device) {
            var baseSpan = Math.max(1, Math.min(parseInt(node && node.props ? node.props.span || 12 : 12, 10) || 12, 12));
            if (device === 'desktop' || !node || !node.responsive || !node.responsive[device]) {
                return baseSpan;
            }
            var deviceSpan = parseInt(node.responsive[device].span, 10);
            if (deviceSpan >= 1 && deviceSpan <= 12) {
                return deviceSpan;
            }
            return baseSpan;
        }

        function getDeviceLabel(device) {
            if (device === 'tablet') {
                return '平板';
            }
            if (device === 'mobile') {
                return '手机';
            }
            return 'PC';
        }

        function getDeviceSizeText(device) {
            if (device === 'tablet') {
                return '834px';
            }
            if (device === 'mobile') {
                return '390px';
            }
            return '100%';
        }

        function toggleElement(element, visible, displayValue) {
            if (!element) {
                return;
            }
            element.style.display = visible ? (displayValue || 'block') : 'none';
        }

        function getCurrentBuilderType() {
            var checked = pageBuilderTypeInputs.find(function (item) {
                return item && item.checked;
            });
            return checked ? checked.value : 'visual';
        }

        function updateFloatingMeta() {
            var pageName = pageNameInput ? pageNameInput.value.trim() : '';
            var pageSlug = pageSlugInput ? pageSlugInput.value.trim() : '';
            var builderType = getCurrentBuilderType();
            var selectedNode = getNodeByPath(selectedPath);
            var selectedText = selectedNode ? ('当前区块：' + (getNodeTitle(selectedNode) || selectedNode.type || '未命名区块')) : '当前未选中区块';

            if (pageFormWorkspaceName) {
                pageFormWorkspaceName.textContent = pageName || '未命名页面';
            }
            if (pageFormFloatingTitle) {
                pageFormFloatingTitle.textContent = pageName || '未命名页面';
            }
            if (pageFormWorkspaceSlug) {
                pageFormWorkspaceSlug.textContent = pageSlug ? ('/p/' + String(pageSlug).replace(/^\/+/, '')) : '保存后生成正式地址';
                pageFormWorkspaceSlug.classList.toggle('is-muted', !pageSlug);
            }
            if (pageFormFloatingSlug) {
                pageFormFloatingSlug.textContent = pageSlug ? ('路径：' + pageSlug) : '路径：待填写';
            }
            if (pageFormWorkspaceMode) {
                pageFormWorkspaceMode.textContent = builderType === 'html' ? 'HTML 代码布局' : 'Visual 布局编辑器';
            }
            if (pageFormFloatingMode) {
                pageFormFloatingMode.textContent = builderType === 'html' ? 'HTML 模式' : 'Visual 模式';
            }
            if (pageFormFloatingSelected) {
                pageFormFloatingSelected.textContent = selectedText;
            }
        }

        function setPageFormDirty(dirty, statusText) {
            pageFormDirty = !!dirty;
            var text = statusText || (dirty ? '未保存变更' : '已同步');
            if (pageFormWorkspaceStatus) {
                pageFormWorkspaceStatus.textContent = text;
            }
            if (pageFormFloatingStatus) {
                pageFormFloatingStatus.textContent = text;
                pageFormFloatingStatus.classList.toggle('is-warning', !!dirty);
                pageFormFloatingStatus.classList.toggle('is-success', !dirty);
            }
            if (pageFormFloatingSubmit) {
                pageFormFloatingSubmit.textContent = '保存页面';
            }
        }

        function markFormDirty(statusText) {
            updateFloatingMeta();
            setPageFormDirty(true, statusText || '未保存变更');
        }

        function serializeFormState() {
            if (!pageFormEditor || typeof FormData === 'undefined') {
                return '';
            }
            var formData = new FormData(pageFormEditor);
            var rows = [];
            formData.forEach(function (value, key) {
                rows.push(key + '=' + value);
            });
            return rows.join('&');
        }

        function refreshPageFormState(statusText) {
            updateFloatingMeta();
            if (!pageFormEditor) {
                return;
            }
            var currentSnapshot = serializeFormState();
            setPageFormDirty(currentSnapshot !== lastSavedSnapshot, statusText);
        }

        function setInspectorTab(tabName) {
            activeInspectorTab = tabName || 'content';
            if (pageBuilderInspectorTabs) {
                [].slice.call(pageBuilderInspectorTabs.querySelectorAll('[data-inspector-tab]')).forEach(function (button) {
                    button.classList.toggle('is-active', button.getAttribute('data-inspector-tab') === activeInspectorTab);
                });
            }
            pageBuilderInspectorPanels.forEach(function (panel) {
                panel.classList.toggle('is-active', panel.getAttribute('data-inspector-panel') === activeInspectorTab);
            });
        }

        function closePanelTips(exceptKey) {
            if (!pageBuilderWorkspace) {
                return;
            }
            [].slice.call(pageBuilderWorkspace.querySelectorAll('[data-panel-tip]')).forEach(function (tip) {
                var keepOpen = exceptKey && tip.getAttribute('data-panel-tip') === exceptKey;
                tip.classList.toggle('is-open', !!keepOpen);
            });
        }

        function getSubpanelKey(panel, fallbackIndex) {
            if (!panel) {
                return 'subpanel_' + fallbackIndex;
            }
            return panel.id || panel.getAttribute('data-subpanel-key') || ('subpanel_' + fallbackIndex);
        }

        function setSubpanelCollapsed(panel, collapsed) {
            if (!panel) {
                return;
            }
            var key = getSubpanelKey(panel, 0);
            collapsedSubpanelKeys[key] = !!collapsed;
            panel.classList.toggle('is-collapsed', !!collapsed);
            var toggle = panel.querySelector('[data-subpanel-toggle]');
            if (toggle) {
                toggle.textContent = collapsed ? '+' : '-';
                toggle.setAttribute('title', collapsed ? '展开' : '收起');
            }
        }

        function getDefaultSubpanelCollapsed(panel, index) {
            var key = getSubpanelKey(panel, index);
            return [
                'builderVisibilityConfig',
                'builderReusableConfig',
                'builderSpacingConfig',
                'builderContainerConfig',
                'builderResponsiveConfig',
                'builderJsonConfig'
            ].indexOf(key) !== -1;
        }

        function initializeCollapsibleSubpanels() {
            if (!inspectorForm) {
                return;
            }
            [].slice.call(inspectorForm.querySelectorAll('.ft-page-builder__subpanel')).forEach(function (panel, index) {
                if (panel.getAttribute('data-subpanel-ready') === '1') {
                    return;
                }
                var title = panel.querySelector('.ft-page-builder__subpanel-title');
                if (!title) {
                    return;
                }
                var desc = panel.querySelector('.ft-page-builder__subpanel-desc');
                var header = document.createElement('div');
                header.className = 'ft-page-builder__subpanel-header';

                var headcopy = document.createElement('div');
                headcopy.className = 'ft-page-builder__subpanel-headcopy';
                headcopy.appendChild(title);
                if (desc) {
                    headcopy.appendChild(desc);
                }

                var toggle = document.createElement('button');
                toggle.type = 'button';
                toggle.className = 'ft-page-builder__subpanel-toggle';
                toggle.setAttribute('data-subpanel-toggle', getSubpanelKey(panel, index));
                toggle.textContent = '-';
                toggle.setAttribute('title', '收起');

                header.appendChild(headcopy);
                header.appendChild(toggle);

                var body = document.createElement('div');
                body.className = 'ft-page-builder__subpanel-body';

                while (panel.firstChild) {
                    body.appendChild(panel.firstChild);
                }
                panel.appendChild(header);
                panel.appendChild(body);
                panel.setAttribute('data-subpanel-ready', '1');
                var key = getSubpanelKey(panel, index);
                var collapsed = collapsedSubpanelKeys.hasOwnProperty(key)
                    ? !!collapsedSubpanelKeys[key]
                    : getDefaultSubpanelCollapsed(panel, index);
                setSubpanelCollapsed(panel, collapsed);
            });
        }

        function initializeCollapsibleHints() {
            if (!inspectorForm) {
                return;
            }
            [].slice.call(inspectorForm.querySelectorAll('.ft-page-builder__field-help[data-help-collapsible="1"]')).forEach(function (hint, index) {
                if (!hint || hint.getAttribute('data-hint-ready') === '1') {
                    return;
                }
                var wrapper = document.createElement('div');
                wrapper.className = 'ft-page-builder__hint';
                wrapper.setAttribute('data-hint-key', hint.id || ('hint_' + index));
                var toggle = document.createElement('button');
                toggle.type = 'button';
                toggle.className = 'ft-page-builder__hint-toggle';
                toggle.setAttribute('data-hint-toggle', wrapper.getAttribute('data-hint-key'));
                toggle.setAttribute('title', '查看说明');
                toggle.textContent = '?';
                var body = document.createElement('div');
                body.className = 'ft-page-builder__hint-body';
                hint.parentNode.insertBefore(wrapper, hint);
                wrapper.appendChild(toggle);
                wrapper.appendChild(body);
                body.appendChild(hint);
                hint.setAttribute('data-hint-ready', '1');
            });
        }

        function scheduleInspectorFloatingPositionSync() {
            if (scheduleInspectorFloatingPositionSync.frameId) {
                return;
            }
            scheduleInspectorFloatingPositionSync.frameId = window.requestAnimationFrame(function () {
                scheduleInspectorFloatingPositionSync.frameId = 0;
                syncInspectorFloatingPosition();
            });
        }

        function syncInspectorFloatingPosition() {
            if (!pageBuilderWorkspace || !pageBuilderInspectorTrack || !pageBuilderInspectorPanel) {
                if (pageBuilderInspectorPanel) {
                    pageBuilderInspectorPanel.classList.remove('is-fixed', 'is-at-bottom');
                    pageBuilderInspectorPanel.style.removeProperty('--inspector-fixed-left');
                    pageBuilderInspectorPanel.style.removeProperty('--inspector-fixed-width');
                }
                return;
            }

            var stickyTop = 16;
            var workspaceRect = pageBuilderWorkspace.getBoundingClientRect();
            var trackRect = pageBuilderInspectorTrack.getBoundingClientRect();
            var panelHeight = pageBuilderInspectorPanel.offsetHeight;
            var canFix = workspaceRect.top <= stickyTop;

            pageBuilderInspectorPanel.classList.remove('is-fixed', 'is-at-bottom');
            pageBuilderInspectorPanel.style.removeProperty('--inspector-fixed-left');
            pageBuilderInspectorPanel.style.removeProperty('--inspector-fixed-width');

            if (!canFix) {
                return;
            }

            pageBuilderInspectorPanel.classList.add('is-fixed');
            pageBuilderInspectorPanel.style.setProperty('--inspector-fixed-left', Math.round(trackRect.left) + 'px');
            pageBuilderInspectorPanel.style.setProperty('--inspector-fixed-width', Math.round(trackRect.width) + 'px');
        }

        function findUploadModelIdentification() {
            var selectedModelId = pageModelSelect ? String(pageModelSelect.value || '') : '';
            var selectedModel = null;
            if (selectedModelId) {
                selectedModel = pageBuilderModels.find(function (model) {
                    return String(model.id) === selectedModelId;
                });
            }
            if (selectedModel && selectedModel.identification) {
                return selectedModel.identification;
            }
            return pageBuilderModels.length ? String(pageBuilderModels[0].identification || '') : '';
        }

        function setFieldHelpState(element, message, isError) {
            if (!element) {
                return;
            }
            element.classList.toggle('ft-page-builder__field-error', !!isError);
            element.textContent = message;
            var wrapper = element.closest('.ft-page-builder__hint');
            if (wrapper) {
                wrapper.classList.add('is-open');
            }
        }

        function getCatalogGroupKey(block) {
            var type = String(block && block.type || '').toLowerCase();
            var name = String(block && block.name || '').toLowerCase();
            if (['section', 'row', 'column'].indexOf(type) !== -1 || name.indexOf('布局') !== -1 || name.indexOf('分栏') !== -1) {
                return 'layout';
            }
            if (type.indexOf('model_') === 0) {
                return 'data';
            }
            if (['heading', 'text', 'image', 'button', 'divider'].indexOf(type) !== -1) {
                return 'basic';
            }
            if (name.indexOf('卡片') !== -1 || name.indexOf('faq') !== -1 || name.indexOf('视频') !== -1 || name.indexOf('提示') !== -1 || name.indexOf('轮播') !== -1 || name.indexOf('表单') !== -1 || name.indexOf('导航') !== -1 || name.indexOf('分享') !== -1 || name.indexOf('二维码') !== -1 || name.indexOf('登录') !== -1) {
                return 'marketing';
            }
            return 'advanced';
        }

        function getCatalogGroupLabel(groupKey) {
            var labels = {
                all: '全部',
                layout: '布局',
                basic: '基础',
                marketing: '商业',
                data: '数据',
                advanced: '高级'
            };
            return labels[groupKey] || '其它';
        }

        function getCatalogGroupToolMark(groupKey) {
            var marks = {
                all: 'ALL',
                layout: 'L',
                basic: 'B',
                marketing: 'M',
                data: 'D',
                advanced: 'A'
            };
            return marks[groupKey] || '?';
        }

        function getCatalogSectionOrder() {
            return ['layout', 'basic', 'marketing', 'data', 'advanced'];
        }

        function isCatalogSectionExpanded(groupKey) {
            if (catalogKeyword.trim()) {
                return true;
            }
            if (Object.prototype.hasOwnProperty.call(catalogExpandedSections, groupKey)) {
                return !!catalogExpandedSections[groupKey];
            }
            return groupKey === 'layout';
        }

        function getCatalogSectionPreviewLimit() {
            return catalogGroup === 'all' ? 3 : 5;
        }

        function getCatalogSectionMeta(groupKey, total, expanded, visibleCount) {
            if (!total) {
                return '当前分组暂无组件';
            }
            if (catalogKeyword.trim()) {
                return '搜索命中 ' + total + ' 个组件';
            }
            if (!expanded && visibleCount < total) {
                return '先看 ' + visibleCount + ' / ' + total;
            }
            return '共 ' + total + ' 个组件';
        }

        function getCatalogCurrentMetaText(groupKey, total) {
            if (catalogKeyword.trim()) {
                return '已在 ' + getCatalogGroupLabel(groupKey) + ' 中搜索到 ' + total + ' 个结果。';
            }
            if (groupKey === 'all') {
                return '查看整套组件库，适合先找方向，再插入具体组件。';
            }
            return '当前工具组：' + getCatalogGroupLabel(groupKey) + '，先挑这组组件再往画布里放。';
        }

        function renderCatalogCard(block) {
            return '' +
                '<div class="ft-page-builder__catalog-card" data-builder-add="' + escapeHtml(block.type || '') + '" data-builder-schema="' + escapeHtml(block.schema || '') + '" title="点击直接插入">' +
                    '<div class="ft-page-builder__catalog-card-head">' +
                        '<h5>' + escapeHtml(block.name || '') + '</h5>' +
                    '</div>' +
                    '<p>' + escapeHtml(block.desc || '') + '</p>' +
                    '<div class="ft-page-builder__catalog-actions">' +
                        '<span class="ft-page-builder__catalog-type">' + escapeHtml(getCatalogGroupLabel(getCatalogGroupKey(block))) + ' / ' + escapeHtml(block.type || '') + '</span>' +
                    '</div>' +
                '</div>';
        }

        function renderCatalogSection(groupKey, blocks) {
            var expanded = isCatalogSectionExpanded(groupKey);
            var previewLimit = getCatalogSectionPreviewLimit();
            var visibleBlocks = expanded ? blocks : blocks.slice(0, previewLimit);
            var metaText = getCatalogSectionMeta(groupKey, blocks.length, expanded, visibleBlocks.length);
            var moreHtml = '';
            if (blocks.length > previewLimit && !catalogKeyword.trim()) {
                moreHtml = '' +
                    '<div class="ft-page-builder__catalog-section-more">' +
                        '<button type="button" class="btn btn-default btn-xs" data-catalog-section-more="' + escapeHtml(groupKey) + '">' + (expanded ? '收起分组' : ('展开其余 ' + (blocks.length - visibleBlocks.length) + ' 个')) + '</button>' +
                    '</div>';
            }
            return '' +
                '<div class="ft-page-builder__catalog-section' + (expanded ? ' is-open' : '') + '">' +
                    '<button type="button" class="ft-page-builder__catalog-section-head" data-catalog-section-toggle="' + escapeHtml(groupKey) + '">' +
                        '<div>' +
                            '<h5 class="ft-page-builder__catalog-section-title">' + escapeHtml(getCatalogGroupLabel(groupKey)) + '</h5>' +
                            '<div class="ft-page-builder__catalog-section-meta">' + escapeHtml(metaText) + '</div>' +
                        '</div>' +
                        '<span class="ft-page-builder__catalog-section-toggle">v</span>' +
                    '</button>' +
                    '<div class="ft-page-builder__catalog-section-body">' +
                        '<div class="ft-page-builder__catalog-section-list">' + visibleBlocks.map(function (block) {
                            return renderCatalogCard(block);
                        }).join('') + '</div>' +
                        moreHtml +
                    '</div>' +
                '</div>';
        }

        function getFilteredCatalog() {
            return blockCatalog.filter(function (block) {
                var groupKey = getCatalogGroupKey(block);
                var keyword = catalogKeyword.trim().toLowerCase();
                var matchesGroup = catalogGroup === 'all' || groupKey === catalogGroup;
                var haystack = [block.name, block.desc, block.type, groupKey].join(' ').toLowerCase();
                var matchesKeyword = !keyword || haystack.indexOf(keyword) !== -1;
                return matchesGroup && matchesKeyword;
            });
        }

        function renderCatalogFilters() {
            if (!pageBuilderCatalogFilters) {
                return;
            }
            var groups = ['all', 'layout', 'basic', 'marketing', 'data', 'advanced'];
            pageBuilderCatalogFilters.innerHTML = groups.map(function (groupKey) {
                var activeClass = catalogGroup === groupKey ? ' is-active' : '';
                return '' +
                    '<button type="button" class="ft-page-builder__catalog-tool' + activeClass + '" data-catalog-group="' + escapeHtml(groupKey) + '" title="' + escapeHtml(getCatalogGroupLabel(groupKey)) + '">' +
                        '<span class="ft-page-builder__catalog-tool-mark">' + escapeHtml(getCatalogGroupToolMark(groupKey)) + '</span>' +
                        '<span class="ft-page-builder__catalog-tool-label">' + escapeHtml(getCatalogGroupLabel(groupKey)) + '</span>' +
                    '</button>';
            }).join('');
        }

        function renderCatalogList() {
            if (!pageBuilderCatalog) {
                return;
            }
            var filtered = getFilteredCatalog();
            if (pageBuilderCatalogCurrentTitle) {
                pageBuilderCatalogCurrentTitle.textContent = catalogKeyword.trim()
                    ? ('搜索：' + getCatalogGroupLabel(catalogGroup))
                    : (catalogGroup === 'all' ? '全部组件' : (getCatalogGroupLabel(catalogGroup) + '组件'));
            }
            if (pageBuilderCatalogCurrentMeta) {
                pageBuilderCatalogCurrentMeta.textContent = getCatalogCurrentMetaText(catalogGroup, filtered.length);
            }
            if (pageBuilderCatalogMeta) {
                pageBuilderCatalogMeta.textContent = '当前显示 ' + filtered.length + ' / ' + blockCatalog.length + ' 个组件，已按商业页面常用场景分组。';
            }
            if (!filtered.length) {
                pageBuilderCatalog.innerHTML = '<div class="ft-page-builder__canvas-empty">没有找到匹配组件。<br>试试换个关键词，或者切回“全部”。</div>';
                return;
            }
            var grouped = {};
            filtered.forEach(function (block) {
                var groupKey = getCatalogGroupKey(block);
                if (!grouped[groupKey]) {
                    grouped[groupKey] = [];
                }
                grouped[groupKey].push(block);
            });
            pageBuilderCatalog.innerHTML = getCatalogSectionOrder().filter(function (groupKey) {
                return grouped[groupKey] && grouped[groupKey].length;
            }).map(function (groupKey) {
                return renderCatalogSection(groupKey, grouped[groupKey]);
            }).join('');
        }

        function getCatalogQuickBlocks() {
            if (catalogGroup !== 'all' && !catalogKeyword.trim()) {
                return [];
            }
            var quickNames = ['区块容器', '标题', '文本', '按钮', '双列布局', '轮播横幅'];
            return quickNames.map(function (name) {
                return blockCatalog.find(function (block) {
                    return block.name === name;
                });
            }).filter(Boolean);
        }

        function getNodeTrail(path) {
            if (!path) {
                return [];
            }
            var segments = path.split('.');
            var trail = [];
            for (var index = 0; index < segments.length; index += 1) {
                if (!/^\d+$/.test(segments[index])) {
                    continue;
                }
                var nodePath = segments.slice(0, index + 1).join('.');
                var node = getNodeByPath(nodePath);
                if (node) {
                    trail.push({
                        path: nodePath,
                        node: node
                    });
                }
            }
            return trail;
        }

        function getInsertTargetMeta() {
            if (!selectedPath) {
                return {
                    title: '首个可用容器',
                    detail: '当前未选中区块，新组件会优先进入第一个 section；如果画布还是空的，会先自动创建空白 section。'
                };
            }
            var currentNode = getNodeByPath(selectedPath);
            if (!currentNode) {
                return {
                    title: '首个可用容器',
                    detail: '当前区块状态已失效，新组件会回退到默认插入策略。'
                };
            }
            var childKey = getChildCollectionKey(currentNode);
            if (childKey) {
                return {
                    title: getNodeTitle(currentNode) + ' 内部',
                    detail: '当前选中的是容器区块，新组件会追加到 `' + childKey + '` 集合末尾，适合继续往这个层级里搭内容。'
                };
            }
            var trail = getNodeTrail(selectedPath);
            var parentNode = trail.length > 1 ? trail[trail.length - 2].node : null;
            return {
                title: getNodeTitle(currentNode) + ' 后方',
                detail: '当前选中的是普通区块，新组件会插入到它的同级位置。' + (parentNode ? ('父级容器：' + getNodeTitle(parentNode) + '。') : '当前位于页面根级。')
            };
        }

        function renderCatalogQuickPanel() {
            var targetMeta = getInsertTargetMeta();
            if (pageBuilderCatalogQuick) {
                var quickBlocks = getCatalogQuickBlocks();
                if (!quickBlocks.length) {
                    pageBuilderCatalogQuick.innerHTML = '';
                } else {
                    pageBuilderCatalogQuick.innerHTML = '' +
                        '<h5 class="ft-page-builder__catalog-quick-title">常用捷径，左右滑动可快速插入</h5>' +
                        '<div class="ft-page-builder__catalog-quick-list">' +
                            quickBlocks.map(function (block) {
                                return '<button type="button" class="ft-page-builder__catalog-quick-btn" data-catalog-quick-add="' + escapeHtml(block.type || '') + '" data-catalog-quick-schema="' + escapeHtml(block.schema || '') + '">' + escapeHtml(block.name || '') + '</button>';
                            }).join('') +
                        '</div>';
                }
            }
            if (pageBuilderCatalogTarget) {
                pageBuilderCatalogTarget.innerHTML = '' +
                    '<strong>当前插入目标：</strong>' + escapeHtml(targetMeta.title || '') + '<br>' +
                    escapeHtml(targetMeta.detail || '');
            }
        }

        function focusBuilderNotice() {
            if (!pageBuilderNotice || typeof pageBuilderNotice.scrollIntoView !== 'function') {
                return;
            }
            pageBuilderNotice.scrollIntoView({
                behavior: 'smooth',
                block: 'nearest'
            });
        }

        function getCatalogBlockByName(name) {
            return blockCatalog.find(function (block) {
                return block.name === name;
            }) || null;
        }

        function getNodePathLabel(path) {
            var node = getNodeByPath(path);
            if (!node) {
                return '当前区块';
            }
            return getNodeTitle(node) + '（' + (node.type || 'block') + '）';
        }

        function getNodeRoleLabel(node) {
            var type = String(node && node.type || '').trim().toLowerCase();
            if (type === 'section') {
                return '页面区块';
            }
            if (type === 'row') {
                return '行容器';
            }
            if (type === 'column') {
                return '列容器';
            }
            return '内容区块';
        }

        function getNodeTypeCode(node) {
            var type = String(node && node.type || '').trim().toLowerCase();
            if (type === 'section') {
                return 'S';
            }
            if (type === 'row') {
                return 'R';
            }
            if (type === 'column') {
                return 'C';
            }
            return 'B';
        }

        function getCollectionLabel(key) {
            if (key === 'children') {
                return '子区块区域';
            }
            if (key === 'blocks') {
                return '列内内容区域';
            }
            return '子级区域';
        }

        function getSelectedParentNodePath() {
            var trail = getNodeTrail(selectedPath);
            return trail.length > 1 ? trail[trail.length - 2].path : '';
        }

        function setBuilderNotice(type, message, actionConfig) {
            if (!pageBuilderNotice) {
                return;
            }
            builderNoticeState = actionConfig || null;
            pageBuilderNotice.className = 'ft-page-builder__notice is-' + escapeHtml(type || 'info');
            pageBuilderNotice.style.display = 'flex';
            var actionsHtml = '<button type="button" class="btn btn-default btn-xs" data-builder-notice="dismiss">知道了</button>';
            if (actionConfig && actionConfig.confirmText) {
                actionsHtml = '' +
                    '<button type="button" class="btn btn-primary btn-xs" data-builder-notice="confirm">' + escapeHtml(actionConfig.confirmText) + '</button>' +
                    '<button type="button" class="btn btn-default btn-xs" data-builder-notice="dismiss">' + escapeHtml(actionConfig.cancelText || '取消') + '</button>';
            }
            pageBuilderNotice.innerHTML = '' +
                '<div>' + escapeHtml(message || '') + '</div>' +
                '<div class="ft-page-builder__notice-actions">' + actionsHtml + '</div>';
            focusBuilderNotice();
        }

        function clearBuilderNotice() {
            if (!pageBuilderNotice) {
                return;
            }
            builderNoticeState = null;
            pageBuilderNotice.style.display = 'none';
            pageBuilderNotice.innerHTML = '';
            pageBuilderNotice.className = 'ft-page-builder__notice';
        }

        function executeBuilderNoticeAction() {
            if (!builderNoticeState || !builderNoticeState.action) {
                clearBuilderNotice();
                return;
            }
            var action = builderNoticeState.action;
            var payload = builderNoticeState.payload || {};
            clearBuilderNotice();
            if (action === 'overwrite-default-schema') {
                layoutSchema.value = defaultSchema;
                builderState = normalizeSchema(defaultSchema);
                selectedPath = '';
                refreshBuilder();
                setBuilderNotice('success', '已覆盖为示例布局 JSON。');
                return;
            }
            if (action === 'delete-node' && payload.path) {
                deleteNode(payload.path);
                setBuilderNotice('success', '区块已删除。');
                return;
            }
            if (action === 'replace-layout-preset' && payload.presetKey) {
                if (applyPresetLayout(payload.presetKey)) {
                    setBuilderNotice('success', payload.kind === 'template'
                        ? '整页模板已清空原页面并导入。'
                        : '区块模板已清空原页面并导入。');
                }
                return;
            }
        }

        function renderImagePreview(src) {
            if (!builderImagePreview) {
                return;
            }
            if (builderImageSourceType && builderImageSourceType.value === 'model_detail') {
                builderImagePreview.innerHTML = '<div class="ft-page-builder__image-empty">当前使用动态图片来源。<br>前台会从模型 `' + escapeHtml((builderImageModel && builderImageModel.value) ? builderImageModel.value : '未绑定') + '` 详情里读取首图。</div>';
                return;
            }
            var imageUrl = normalizeBuilderPreviewImageSrc(src);
            if (!imageUrl) {
                builderImagePreview.innerHTML = '<div class="ft-page-builder__image-empty">当前没有图片。<br>你可以上传图片，或直接粘贴图片地址。</div>';
                return;
            }
            builderImagePreview.innerHTML = '<img src="' + escapeHtml(imageUrl) + '" alt="预览图">';
        }

        function getBuilderPreviewPlaceholderImage() {
            return 'data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22960%22 height=%22640%22 viewBox=%220 0 960 640%22%3E%3Crect width=%22960%22 height=%22640%22 rx=%2236%22 fill=%22%23e2e8f0%22/%3E%3Cpath d=%22M152 470l164-182 120 132 112-126 260 176H152z%22 fill=%22%23cbd5e1%22/%3E%3Ccircle cx=%22312%22 cy=%22208%22 r=%2254%22 fill=%22%2394a3b8%22/%3E%3Ctext x=%2250%25%22 y=%2288%25%22 text-anchor=%22middle%22 font-size=%2238%22 font-family=%22Arial,sans-serif%22 fill=%22%23475569%22%3EImage Placeholder%3C/text%3E%3C/svg%3E';
        }

        function normalizeBuilderPreviewImageSrc(src) {
            var imageUrl = String(src || '').trim();
            if (!imageUrl) {
                return '';
            }
            if (/\/uploads\/demo\.jpg$/i.test(imageUrl) || /^demo\.jpg$/i.test(imageUrl)) {
                return getBuilderPreviewPlaceholderImage();
            }
            return imageUrl;
        }

        function uploadBuilderImage(file) {
            if (!file) {
                return;
            }
            var modelIdentification = findUploadModelIdentification();
            if (!modelIdentification) {
                setFieldHelpState(builderImageUploadHint, '当前没有可用模型，暂时无法走后台上传。请先创建一个模型，或直接粘贴图片地址。', true);
                return;
            }
            var request = new XMLHttpRequest();
            var formData = new FormData();
            formData.append('file', file);
            formData.append('action', 'uploadImg');
            formData.append('model', modelIdentification);
            formData.append('moduleName', @json($moduleName));
            request.open('POST', pageBuilderUploadUrl, true);
            setFieldHelpState(builderImageUploadHint, '正在上传图片：' + file.name + ' ...', false);
            request.onreadystatechange = function () {
                if (request.readyState !== 4) {
                    return;
                }
                if (request.status < 200 || request.status >= 300) {
                    setFieldHelpState(builderImageUploadHint, '上传失败，服务没有返回有效结果。你也可以先手动粘贴图片地址。', true);
                    return;
                }
                var response = {};
                try {
                    response = JSON.parse(request.responseText || '{}');
                } catch (error) {
                    response = {};
                }
                if (!response.location) {
                    setFieldHelpState(builderImageUploadHint, response.msg || '上传失败，请检查上传配置或文件格式。', true);
                    return;
                }
                if (builderImageSrc) {
                    builderImageSrc.value = response.location;
                }
                applyVisualConfig();
                renderImagePreview(response.location);
                setFieldHelpState(builderImageUploadHint, '上传成功，已自动回填图片地址。当前上传模型：' + modelIdentification, false);
            };
            request.send(formData);
        }

        function buildReusableTitle(node) {
            if (!node) {
                return '未命名区块';
            }
            if (node.props && node.props.text) {
                return String(node.props.text).trim().slice(0, 24) || (node.type || '区块');
            }
            if (node.props && node.props.alt) {
                return String(node.props.alt).trim().slice(0, 24) || (node.type || '区块');
            }
            return node.type || '区块';
        }

        function getQuickPresetsForNode(node) {
            if (!node) {
                return [];
            }
            var presetMap = {
                section: [
                    {key: 'section-compact', label: '紧凑留白'},
                    {key: 'section-regular', label: '常规留白'},
                    {key: 'section-hero', label: '首屏留白'},
                    {key: 'section-light', label: '浅色背景'},
                    {key: 'section-dark', label: '深色背景'},
                    {key: 'section-contained', label: '居中内容'},
                    {key: 'section-full', label: '通栏内容'}
                ],
                heading: [
                    {key: 'heading-left', label: '左对齐'},
                    {key: 'heading-center', label: '居中'},
                    {key: 'heading-large', label: '大标题'},
                    {key: 'heading-medium', label: '中标题'},
                    {key: 'heading-brand', label: '品牌色'}
                ],
                text: [
                    {key: 'text-body', label: '正文'},
                    {key: 'text-small', label: '小字'},
                    {key: 'text-muted', label: '浅色文案'},
                    {key: 'text-center', label: '居中段落'}
                ],
                button: [
                    {key: 'button-primary', label: '主按钮'},
                    {key: 'button-secondary', label: '浅色按钮'},
                    {key: 'button-outline', label: '描边按钮'},
                    {key: 'button-pill', label: '胶囊圆角'}
                ],
                image: [
                    {key: 'image-full', label: '满宽图片'},
                    {key: 'image-rounded', label: '圆角图'},
                    {key: 'image-cover', label: 'cover'},
                    {key: 'image-contain', label: 'contain'}
                ]
            };
            return presetMap[node.type] || [];
        }

        function renderQuickPresets(node) {
            if (!builderQuickPresetWrap || !builderQuickPresetList) {
                return;
            }
            var presets = getQuickPresetsForNode(node);
            toggleElement(builderQuickPresetWrap, presets.length > 0, 'block');
            if (!presets.length) {
                builderQuickPresetList.innerHTML = '';
                return;
            }
            if (builderQuickPresetHint) {
                builderQuickPresetHint.textContent = '当前区块：' + (node.type || 'block') + '。点击下面的预设按钮会直接写入当前区块的常用样式。';
            }
            builderQuickPresetList.innerHTML = presets.map(function (preset) {
                return '<button type="button" class="btn btn-default btn-xs" data-quick-preset="' + escapeHtml(preset.key) + '">' + escapeHtml(preset.label) + '</button>';
            }).join('');
        }

        function applyQuickPreset(presetKey) {
            var node = getNodeByPath(selectedPath);
            if (!node) {
                return;
            }
            switch (presetKey) {
                case 'section-compact':
                    setNodeNestedValue(node, 'style', 'padding', '24px 0');
                    break;
                case 'section-regular':
                    setNodeNestedValue(node, 'style', 'padding', '48px 0');
                    break;
                case 'section-hero':
                    setNodeNestedValue(node, 'style', 'padding', '88px 0');
                    break;
                case 'section-light':
                    setNodeNestedValue(node, 'style', 'background', '#f8fafc');
                    break;
                case 'section-dark':
                    setNodeNestedValue(node, 'style', 'background', '#0f172a');
                    break;
                case 'section-contained':
                    setNodeNestedValue(node, 'props', 'contentWidth', 'contained');
                    setNodeNestedValue(node, 'props', 'innerWidth', '1180px');
                    break;
                case 'section-full':
                    setNodeNestedValue(node, 'props', 'contentWidth', null);
                    setNodeNestedValue(node, 'props', 'innerWidth', null);
                    break;
                case 'heading-left':
                    setNodeNestedValue(node, 'style', 'textAlign', 'left');
                    break;
                case 'heading-center':
                    setNodeNestedValue(node, 'style', 'textAlign', 'center');
                    break;
                case 'heading-large':
                    setNodeNestedValue(node, 'props', 'level', 'h1');
                    setNodeNestedValue(node, 'style', 'fontSize', '48px');
                    break;
                case 'heading-medium':
                    setNodeNestedValue(node, 'props', 'level', 'h2');
                    setNodeNestedValue(node, 'style', 'fontSize', '32px');
                    break;
                case 'heading-brand':
                    setNodeNestedValue(node, 'style', 'color', '#2563eb');
                    break;
                case 'text-body':
                    setNodeNestedValue(node, 'style', 'fontSize', '16px');
                    setNodeNestedValue(node, 'style', 'lineHeight', '1.8');
                    setNodeNestedValue(node, 'style', 'color', '#334155');
                    break;
                case 'text-small':
                    setNodeNestedValue(node, 'style', 'fontSize', '14px');
                    setNodeNestedValue(node, 'style', 'lineHeight', '1.7');
                    break;
                case 'text-muted':
                    setNodeNestedValue(node, 'style', 'color', '#64748b');
                    break;
                case 'text-center':
                    setNodeNestedValue(node, 'style', 'textAlign', 'center');
                    break;
                case 'button-primary':
                    setNodeNestedValue(node, 'style', 'background', '#2563eb');
                    setNodeNestedValue(node, 'style', 'color', '#ffffff');
                    setNodeNestedValue(node, 'style', 'padding', '12px 22px');
                    break;
                case 'button-secondary':
                    setNodeNestedValue(node, 'style', 'background', '#eff6ff');
                    setNodeNestedValue(node, 'style', 'color', '#1d4ed8');
                    break;
                case 'button-outline':
                    setNodeNestedValue(node, 'style', 'background', 'transparent');
                    setNodeNestedValue(node, 'style', 'color', '#2563eb');
                    setNodeNestedValue(node, 'style', 'border', '1px solid #2563eb');
                    break;
                case 'button-pill':
                    setNodeNestedValue(node, 'style', 'borderRadius', '999px');
                    break;
                case 'image-full':
                    setNodeNestedValue(node, 'style', 'width', '100%');
                    break;
                case 'image-rounded':
                    setNodeNestedValue(node, 'style', 'borderRadius', '20px');
                    break;
                case 'image-cover':
                    setNodeNestedValue(node, 'style', 'objectFit', 'cover');
                    break;
                case 'image-contain':
                    setNodeNestedValue(node, 'style', 'objectFit', 'contain');
                    break;
                default:
                    return;
            }
            refreshBuilder();
        }

        function getStylePresetGroups(node) {
            if (!node) {
                return [];
            }
            var palettePrimary = [
                {label: '主蓝', value: '#2563eb'},
                {label: '深蓝', value: '#1d4ed8'},
                {label: '青绿', value: '#0f766e'},
                {label: '橙色', value: '#ea580c'},
                {label: '深灰', value: '#0f172a'},
                {label: '浅灰', value: '#f8fafc'},
                {label: '白色', value: '#ffffff'}
            ];
            var textPalette = [
                {label: '标题黑', value: '#0f172a'},
                {label: '正文灰', value: '#334155'},
                {label: '浅文案', value: '#64748b'},
                {label: '品牌蓝', value: '#2563eb'},
                {label: '白色', value: '#ffffff'}
            ];
            var groups = {
                section: [
                    {title: '背景颜色', desc: '快速给区块换通栏背景色。', mode: 'color', action: 'section-background', options: palettePrimary},
                    {title: '区块留白', desc: '快速调整整块的上下内边距。', mode: 'chip', action: 'section-padding', options: [
                        {label: '24px', value: '24px 0'},
                        {label: '48px', value: '48px 0'},
                        {label: '72px', value: '72px 0'},
                        {label: '96px', value: '96px 0'}
                    ]}
                ],
                heading: [
                    {title: '标题颜色', desc: '直接改标题主色。', mode: 'color', action: 'heading-color', options: textPalette},
                    {title: '标题字号', desc: '快速切换标题尺寸。', mode: 'chip', action: 'heading-font-size', options: [
                        {label: '28px', value: '28px'},
                        {label: '36px', value: '36px'},
                        {label: '48px', value: '48px'},
                        {label: '60px', value: '60px'}
                    ]}
                ],
                text: [
                    {title: '文本颜色', desc: '快速切换正文颜色层级。', mode: 'color', action: 'text-color', options: textPalette},
                    {title: '文字大小', desc: '适合正文、说明、小字场景。', mode: 'chip', action: 'text-font-size', options: [
                        {label: '14px', value: '14px'},
                        {label: '16px', value: '16px'},
                        {label: '18px', value: '18px'},
                        {label: '20px', value: '20px'}
                    ]}
                ],
                button: [
                    {title: '按钮背景', desc: '点击切换按钮主背景色。', mode: 'color', action: 'button-background', options: palettePrimary},
                    {title: '按钮文字', desc: '快速设定按钮文字颜色。', mode: 'color', action: 'button-color', options: textPalette},
                    {title: '按钮圆角', desc: '直接切换按钮圆角风格。', mode: 'chip', action: 'button-radius', options: [
                        {label: '0', value: '0'},
                        {label: '8px', value: '8px'},
                        {label: '16px', value: '16px'},
                        {label: '999px', value: '999px'}
                    ]},
                    {title: '按钮内边距', desc: '快速调整按钮尺寸和点击感。', mode: 'chip', action: 'button-padding', options: [
                        {label: '紧凑', value: '8px 16px'},
                        {label: '标准', value: '12px 20px'},
                        {label: '舒展', value: '14px 28px'},
                        {label: '大按钮', value: '16px 32px'}
                    ]}
                ],
                image: [
                    {title: '图片圆角', desc: '快速切换图片边角风格。', mode: 'chip', action: 'image-radius', options: [
                        {label: '0', value: '0'},
                        {label: '12px', value: '12px'},
                        {label: '20px', value: '20px'},
                        {label: '32px', value: '32px'}
                    ]},
                    {title: '图片宽度', desc: '一键切换常见显示宽度。', mode: 'chip', action: 'image-width', options: [
                        {label: '100%', value: '100%'},
                        {label: '80%', value: '80%'},
                        {label: '60%', value: '60%'},
                        {label: '320px', value: '320px'}
                    ]}
                ]
            };
            return groups[node.type] || [];
        }

        function renderStylePresets(node) {
            if (!builderStylePresetWrap || !builderStylePresetList) {
                return;
            }
            var groups = getStylePresetGroups(node);
            toggleElement(builderStylePresetWrap, groups.length > 0, 'block');
            if (!groups.length) {
                builderStylePresetList.innerHTML = '';
                return;
            }
            if (builderStylePresetHint) {
                builderStylePresetHint.textContent = '当前区块：' + (node.type || 'block') + '。点击颜色块或尺寸预设即可直接写入当前样式。';
            }
            builderStylePresetList.innerHTML = groups.map(function (group) {
                var optionsHtml = (group.options || []).map(function (option) {
                    if (group.mode === 'color') {
                        return '<button type="button" class="ft-page-builder__style-color" data-style-action="' + escapeHtml(group.action) + '" data-style-value="' + escapeHtml(option.value) + '">' +
                            '<span class="ft-page-builder__style-color-dot" style="background:' + escapeHtml(option.value) + ';"></span>' +
                            '<span>' + escapeHtml(option.label) + '</span>' +
                        '</button>';
                    }
                    return '<button type="button" class="ft-page-builder__style-chip" data-style-action="' + escapeHtml(group.action) + '" data-style-value="' + escapeHtml(option.value) + '">' + escapeHtml(option.label) + '</button>';
                }).join('');
                return '<div class="ft-page-builder__style-group">' +
                    '<h6 class="ft-page-builder__style-group-title">' + escapeHtml(group.title) + '</h6>' +
                    '<p class="ft-page-builder__style-group-desc">' + escapeHtml(group.desc || '') + '</p>' +
                    '<div class="' + (group.mode === 'color' ? 'ft-page-builder__style-color-list' : 'ft-page-builder__style-chip-list') + '">' + optionsHtml + '</div>' +
                '</div>';
            }).join('');
        }

        function applyStylePreset(action, value) {
            var node = getNodeByPath(selectedPath);
            if (!node) {
                return;
            }
            switch (action) {
                case 'section-background':
                    setNodeNestedValue(node, 'style', 'background', value);
                    break;
                case 'section-padding':
                    setNodeNestedValue(node, 'style', 'padding', value);
                    break;
                case 'heading-color':
                    setNodeNestedValue(node, 'style', 'color', value);
                    break;
                case 'heading-font-size':
                    setNodeNestedValue(node, 'style', 'fontSize', value);
                    break;
                case 'text-color':
                    setNodeNestedValue(node, 'style', 'color', value);
                    break;
                case 'text-font-size':
                    setNodeNestedValue(node, 'style', 'fontSize', value);
                    break;
                case 'button-background':
                    setNodeNestedValue(node, 'style', 'background', value);
                    break;
                case 'button-color':
                    setNodeNestedValue(node, 'style', 'color', value);
                    break;
                case 'button-radius':
                    setNodeNestedValue(node, 'style', 'borderRadius', value);
                    break;
                case 'button-padding':
                    setNodeNestedValue(node, 'style', 'padding', value);
                    break;
                case 'image-radius':
                    setNodeNestedValue(node, 'style', 'borderRadius', value);
                    break;
                case 'image-width':
                    setNodeNestedValue(node, 'style', 'width', value);
                    break;
                default:
                    return;
            }
            refreshBuilder();
        }

        function loadReusableBlocks() {
            try {
                var raw = window.localStorage.getItem(reusableStorageKey);
                var parsed = raw ? JSON.parse(raw) : [];
                return Array.isArray(parsed) ? parsed : [];
            } catch (error) {
                return [];
            }
        }

        function saveReusableBlocks(items) {
            try {
                window.localStorage.setItem(reusableStorageKey, JSON.stringify(items || []));
                return true;
            } catch (error) {
                return false;
            }
        }

        function renderReusableBlocks() {
            if (!builderReusableList) {
                return;
            }
            var items = loadReusableBlocks();
            if (!items.length) {
                builderReusableList.innerHTML = '<div class="ft-page-builder__image-empty">当前还没有保存过复用区块。<br>先选中一个区块，再点“保存当前区块”。</div>';
                return;
            }
            builderReusableList.innerHTML = items.map(function (item) {
                return '' +
                    '<div class="ft-page-builder__reuse-item">' +
                        '<div class="ft-page-builder__reuse-head">' +
                            '<h6 class="ft-page-builder__reuse-name">' + escapeHtml(item.name || '未命名区块') + '</h6>' +
                            '<span class="ft-page-builder__catalog-type">' + escapeHtml(item.type || 'block') + '</span>' +
                        '</div>' +
                        '<div class="ft-page-builder__reuse-meta">可直接插入到当前区块后面，或替换当前选中区块。</div>' +
                        '<div class="ft-page-builder__reuse-actions">' +
                            '<button type="button" class="btn btn-default btn-xs" data-reuse-action="insert" data-reuse-id="' + escapeHtml(item.id) + '">插入</button>' +
                            '<button type="button" class="btn btn-default btn-xs" data-reuse-action="replace" data-reuse-id="' + escapeHtml(item.id) + '">替换当前</button>' +
                            '<button type="button" class="btn btn-danger btn-xs" data-reuse-action="delete" data-reuse-id="' + escapeHtml(item.id) + '">删除</button>' +
                        '</div>' +
                    '</div>';
            }).join('');
        }

        function saveCurrentReusableBlock() {
            var node = getNodeByPath(selectedPath);
            if (!node) {
                setFieldHelpState(builderReusableHint, '请先在画布里选中一个区块，再保存为复用区块。', true);
                return;
            }
            var items = loadReusableBlocks();
            var name = builderReusableName && builderReusableName.value.trim() ? builderReusableName.value.trim() : buildReusableTitle(node);
            items.unshift({
                id: uniqueId('reuse'),
                name: name,
                type: node.type || 'block',
                node: deepClone(node)
            });
            items = items.slice(0, 30);
            if (!saveReusableBlocks(items)) {
                setFieldHelpState(builderReusableHint, '保存失败，当前浏览器可能禁用了本地存储。', true);
                return;
            }
            if (builderReusableName) {
                builderReusableName.value = '';
            }
            setFieldHelpState(builderReusableHint, '已保存复用区块：' + name, false);
            renderReusableBlocks();
        }

        function applyReusableBlock(reuseId, action) {
            var items = loadReusableBlocks();
            var target = items.find(function (item) {
                return item.id === reuseId;
            });
            if (!target || !target.node) {
                return;
            }
            if (action === 'delete') {
                saveReusableBlocks(items.filter(function (item) {
                    return item.id !== reuseId;
                }));
                renderReusableBlocks();
                return;
            }
            var reusableNode = ensureNode(deepClone(target.node));
            if (action === 'replace' && selectedPath) {
                var parentCollectionPath = getParentCollectionPath(selectedPath);
                var parentCollection = getCollectionByPath(parentCollectionPath);
                var index = getPathIndex(selectedPath);
                if (parentCollection && parentCollection[index]) {
                    parentCollection[index] = reusableNode;
                    selectedPath = parentCollectionPath + '.' + index;
                    refreshBuilder();
                    setFieldHelpState(builderReusableHint, '已用复用区块替换当前区块：' + (target.name || '未命名区块'), false);
                    return;
                }
            }
            insertNode(reusableNode);
            setFieldHelpState(builderReusableHint, '已插入复用区块：' + (target.name || '未命名区块'), false);
        }

        function getSectionInnerStyle(props) {
            if (!props || props.contentWidth !== 'contained') {
                return '';
            }
            var innerWidth = String(props.innerWidth || '1180px').trim();
            if (!innerWidth) {
                return '';
            }
            return ' style="max-width:' + escapeHtml(innerWidth) + ';"';
        }

        function getNodeTitle(node) {
            if (!node) {
                return '未命名区块';
            }
            if (node.type === 'model_list') {
                return '模型列表';
            }
            if (node.type === 'model_detail') {
                return '模型详情';
            }
            if (node.type === 'divider') {
                return '分隔线';
            }
            if (node.props && node.props.text) {
                return String(node.props.text).slice(0, 28);
            }
            if (node.props && node.props.title) {
                return String(node.props.title).slice(0, 28);
            }
            if (node.props && node.props.html) {
                return 'HTML 代码区块';
            }
            if (node.props && node.props.src) {
                return String(node.props.src).slice(0, 28);
            }
            return node.type || 'block';
        }

        function getNodePreview(node) {
            if (!node) {
                return '';
            }
            if (node.type === 'carousel') {
                if (node.props && node.props.source_type === 'model_list') {
                    return '轮播数据源：模型 ' + String(node.props.model || '未绑定') + ' / ' + String(node.props.limit || 3) + ' 条';
                }
                if (node.props && Array.isArray(node.props.slides) && node.props.slides[0]) {
                    return '轮播首屏：' + String(node.props.slides[0].title || '未命名轮播').slice(0, 48);
                }
            }
            if (node.type === 'video') {
                return '视频来源：' + (node.props && node.props.source_type === 'mp4' ? 'MP4 直链' : '嵌入地址');
            }
            if (node.type === 'model_list') {
                return '列表来源：模型 ' + String(node.props && node.props.model || '未绑定') + ' / ' + String(node.props && node.props.limit || 6) + ' 条';
            }
            if (node.type === 'model_detail') {
                return '详情来源：模型 ' + String(node.props && node.props.model || '未绑定') + ' / ID ' + String(node.props && node.props.record_id || '最新');
            }
            if (node.type === 'divider') {
                return '线条：' + String(node.style && node.style.borderTopStyle || 'solid') + ' / ' + String(node.style && node.style.borderColor || '#e5e7eb');
            }
            if (node.props && node.props.text) {
                return String(node.props.text).slice(0, 80);
            }
            if (node.props && node.props.html) {
                return String(node.props.html).replace(/\s+/g, ' ').slice(0, 80);
            }
            if (node.props && node.props.href) {
                return '跳转到 ' + node.props.href;
            }
            if (node.props && node.props.title && node.type === 'navigation') {
                return '导航：' + String(node.props.title).slice(0, 24);
            }
            if (node.props && node.props.title && node.type === 'login_box') {
                return '登录：' + String(node.props.title).slice(0, 24);
            }
            if (node.props && node.props.src) {
                return '资源地址 ' + node.props.src;
            }
            var childKey = getChildCollectionKey(node);
            if (childKey && Array.isArray(node[childKey])) {
                return '包含 ' + node[childKey].length + ' 个子区块，可继续拖入内容';
            }
            return '点击右侧属性面板继续配置';
        }

        function getNodeHelperText(node) {
            var childKey = getChildCollectionKey(node);
            if (childKey) {
                return '整卡可直接选中编辑；拖拽可排序；容器内部还能直接点快捷按钮加行或加列。';
            }
            return '整卡可直接选中编辑；拖拽可调整顺序；同级插入可用区块间的加号。';
        }

        function renderSelectionBar() {
            if (!pageBuilderSelectionBar) {
                return;
            }
            var node = getNodeByPath(selectedPath);
            if (pageBuilderSelectionBreadcrumb) {
                if (!node) {
                    pageBuilderSelectionBreadcrumb.classList.remove('is-visible');
                    pageBuilderSelectionBreadcrumb.innerHTML = '';
                } else {
                    var trail = getNodeTrail(selectedPath);
                    pageBuilderSelectionBreadcrumb.classList.add('is-visible');
                    pageBuilderSelectionBreadcrumb.innerHTML = trail.map(function (item, index) {
                        var isCurrent = index === trail.length - 1;
                        return '' +
                            '<button type="button" class="ft-page-builder__breadcrumb-item' + (isCurrent ? ' is-current' : '') + '" data-breadcrumb-path="' + escapeHtml(item.path || '') + '">' +
                                '<span>' + escapeHtml(item.node.type || 'block') + '</span>' +
                                '<strong>' + escapeHtml(getNodeTitle(item.node)) + '</strong>' +
                            '</button>';
                    }).join('');
                }
            }
            if (!node) {
                pageBuilderSelectionBar.style.display = 'none';
                pageBuilderSelectionBar.innerHTML = '';
                return;
            }
            var childKey = getChildCollectionKey(node);
            var childCount = childKey && Array.isArray(node[childKey]) ? node[childKey].length : 0;
            pageBuilderSelectionBar.style.display = 'flex';
            var visibilitySummary = getVisibilitySummary(node);
            pageBuilderSelectionBar.innerHTML = '' +
                '<div class="ft-page-builder__selection-main">' +
                    '<strong>' + escapeHtml(getNodeTitle(node)) + '</strong>' +
                    '<span>类型：' + escapeHtml(node.type || 'block') + '，路径：' + escapeHtml(selectedPath) + '，' + (childKey ? ('当前有 ' + childCount + ' 个子项') : '当前是叶子区块') + (visibilitySummary ? ('，条件：' + escapeHtml(visibilitySummary)) : '') + '</span>' +
                '</div>' +
                '<div class="ft-page-builder__selection-actions">' +
                    '<button type="button" class="btn btn-default btn-xs" data-selection-action="locate-preview">定位到预览</button>' +
                    '<button type="button" class="btn btn-default btn-xs" data-selection-action="locate-editor">定位到组件编辑器</button>' +
                    '<button type="button" class="btn btn-default btn-xs" data-selection-action="duplicate">复制区块</button>' +
                    '<button type="button" class="btn btn-default btn-xs" data-selection-action="up">上移</button>' +
                    '<button type="button" class="btn btn-default btn-xs" data-selection-action="down">下移</button>' +
                    '<button type="button" class="btn btn-default btn-xs" data-selection-action="save-reuse">保存复用</button>' +
                    '<button type="button" class="btn btn-danger btn-xs" data-selection-action="delete">删除区块</button>' +
                '</div>';
        }

        function renderInspectorSummary(node) {
            if (!pageBuilderInspectorSummary) {
                return;
            }
            if (pageBuilderInspectorOutline) {
                if (!node) {
                    pageBuilderInspectorOutline.classList.remove('is-visible');
                    pageBuilderInspectorOutline.innerHTML = '';
                } else {
                    var trail = getNodeTrail(selectedPath);
                    var parentItem = trail.length > 1 ? trail[trail.length - 2] : null;
                    var childKey = getChildCollectionKey(node);
                    var childCount = childKey && Array.isArray(node[childKey]) ? node[childKey].length : 0;
                    var previewText = getNodePreview(node) || '这个区块当前还没有明显的文案或链接预览。';
                    var insertHint = childKey
                        ? '新组件会继续进入当前容器内部。'
                        : '新组件会插入到当前区块后方。';
                    var visibilitySummary = getVisibilitySummary(node) || '当前没有额外显示隐藏条件。';
                    pageBuilderInspectorOutline.className = 'ft-page-builder__inspector-outline is-visible' + (inspectorOutlineExpanded ? ' is-expanded' : '');
                    pageBuilderInspectorOutline.innerHTML = '' +
                        '<button type="button" class="ft-page-builder__inspector-outline-head" data-inspector-outline-toggle="1" aria-expanded="' + (inspectorOutlineExpanded ? 'true' : 'false') + '">' +
                            '<div>' +
                                '<h5 class="ft-page-builder__inspector-outline-title">当前区块结构概览</h5>' +
                                '<div class="ft-page-builder__inspector-outline-summary">第 ' + escapeHtml(String(trail.length || 1)) + ' 级 / 父级：' + escapeHtml(parentItem ? getNodeTitle(parentItem.node) : '页面根级') + '</div>' +
                            '</div>' +
                            '<span class="ft-page-builder__inspector-outline-toggle">v</span>' +
                        '</button>' +
                        '<div class="ft-page-builder__inspector-outline-body">' +
                            '<div class="ft-page-builder__inspector-outline-grid">' +
                                '<div class="ft-page-builder__inspector-outline-item"><strong>结构位置</strong>第 ' + escapeHtml(String(trail.length || 1)) + ' 级 / ' + escapeHtml(node.type || 'block') + '</div>' +
                                '<div class="ft-page-builder__inspector-outline-item"><strong>父级容器</strong>' + escapeHtml(parentItem ? getNodeTitle(parentItem.node) : '页面根级') + '</div>' +
                                '<div class="ft-page-builder__inspector-outline-item"><strong>容器能力</strong>' + escapeHtml(childKey ? ('可容纳 ' + childKey + '，当前 ' + childCount + ' 个子项') : '当前是叶子区块，没有子级容器') + '</div>' +
                                '<div class="ft-page-builder__inspector-outline-item"><strong>编辑提示</strong>' + escapeHtml(insertHint + ' ' + previewText) + '</div>' +
                                '<div class="ft-page-builder__inspector-outline-item"><strong>显示条件</strong>' + escapeHtml(visibilitySummary) + '</div>' +
                            '</div>' +
                        '</div>';
                }
            }
            if (!node) {
                pageBuilderInspectorSummary.style.display = 'none';
                pageBuilderInspectorSummary.innerHTML = '';
                return;
            }
            pageBuilderInspectorSummary.style.display = 'flex';
            pageBuilderInspectorSummary.innerHTML = '' +
                '<div>' +
                    '<strong>' + escapeHtml(getNodeTitle(node)) + '</strong>' +
                    '<span>当前编辑 `' + escapeHtml(node.type || 'block') + '`，常用改内容，按需再切到样式、布局或 JSON。</span>' +
                '</div>';
        }

        function renderInspectorStickyActions(node) {
            if (!pageBuilderInspectorStickyActions) {
                return;
            }
            if (!node) {
                pageBuilderInspectorStickyActions.classList.remove('is-visible');
                pageBuilderInspectorStickyActions.innerHTML = '';
                return;
            }
            pageBuilderInspectorStickyActions.classList.add('is-visible');
            pageBuilderInspectorStickyActions.innerHTML = '' +
                '<div class="ft-page-builder__inspector-sticky-meta">当前区块：<strong>' + escapeHtml(getNodeTitle(node)) + '</strong> / ' + escapeHtml(node.type || 'block') + '</div>' +
                '<div class="ft-page-builder__inspector-actions">' +
                    '<button type="button" class="btn btn-default btn-xs" data-inspector-sticky-action="locate-preview">定位到预览</button>' +
                    '<button type="button" class="btn btn-default btn-xs" data-inspector-sticky-action="locate-editor">定位到组件编辑器</button>' +
                '</div>';
        }

        function getQuickInsertBlocks() {
            return quickInsertBlockTypes.map(function (type) {
                return blockCatalog.find(function (block) {
                    return block.type === type;
                });
            }).filter(Boolean);
        }

        function createDefaultColumnBlocks(titleText, bodyText) {
            return [
                {
                    type: 'heading',
                    props: {
                        level: 'h3',
                        text: titleText || '列标题'
                    },
                    style: {
                        margin: '0 0 12px'
                    }
                },
                {
                    type: 'text',
                    props: {
                        text: bodyText || '这里继续补充当前列的说明文案、图片、按钮或其它业务区块。'
                    }
                }
            ];
        }

        function createColumnNode(span, titleText, bodyText) {
            return ensureNode({
                type: 'column',
                props: {
                    span: span || 6
                },
                blocks: createDefaultColumnBlocks(titleText, bodyText)
            });
        }

        function createRowLayoutNode(spans) {
            return ensureNode({
                type: 'row',
                style: {
                    gap: '20px'
                },
                children: (spans || [12]).map(function (span, index) {
                    return createColumnNode(span, '列 ' + (index + 1), '这里是第 ' + (index + 1) + ' 列内容。');
                })
            });
        }

        function insertNodesIntoContainer(targetPath, nodes) {
            var targetNode = getNodeByPath(targetPath);
            var childKey = getChildCollectionKey(targetNode);
            if (!targetNode || !childKey || !nodes || !nodes.length) {
                return;
            }
            if (!Array.isArray(targetNode[childKey])) {
                targetNode[childKey] = [];
            }
            var startIndex = targetNode[childKey].length;
            nodes.forEach(function (node) {
                targetNode[childKey].push(ensureNode(node));
            });
            selectedPath = targetPath + '.' + childKey + '.' + startIndex;
            refreshBuilder();
        }

        function createContainerQuickNodes(kind) {
            var block;
            if (kind === 'section-row') {
                return [createRowLayoutNode([12])];
            }
            if (kind === 'section-two-columns') {
                return [createRowLayoutNode([6, 6])];
            }
            if (kind === 'section-three-columns') {
                return [createRowLayoutNode([4, 4, 4])];
            }
            if (kind === 'section-four-columns') {
                return [createRowLayoutNode([3, 3, 3, 3])];
            }
            if (kind === 'row-column-12') {
                return [createColumnNode(12, '整宽列', '适合单列说明、大图或整块表单。')];
            }
            if (kind === 'row-column-6') {
                return [createColumnNode(6, '半宽列', '适合双列内容。')];
            }
            if (kind === 'row-column-4') {
                return [createColumnNode(4, '三分列', '适合三列卡片或卖点展示。')];
            }
            if (kind === 'column-heading-text') {
                return createDefaultColumnBlocks('模块标题', '这里是当前列的正文说明。');
            }
            if (kind === 'column-form') {
                block = getCatalogBlockByName('线索表单');
                return block ? [createNodeFromCatalog(block.type, block.schema)] : [];
            }
            if (kind === 'column-video') {
                block = getCatalogBlockByName('视频嵌入');
                return block ? [createNodeFromCatalog(block.type, block.schema)] : [];
            }
            if (kind === 'column-carousel') {
                block = getCatalogBlockByName('轮播横幅');
                return block ? [createNodeFromCatalog(block.type, block.schema)] : [];
            }
            return [];
        }

        function buildDropzoneActions(node, path) {
            var groups = [];
            if (!node || !path) {
                return '';
            }
            if (node.type === 'section') {
                groups = [
                    {
                        title: '布局骨架',
                        items: [
                            {key: 'section-row', label: '加一行'},
                            {key: 'section-two-columns', label: '双列 6-6'},
                            {key: 'section-three-columns', label: '三列 4-4-4'},
                            {key: 'section-four-columns', label: '四列 3-3-3-3'}
                        ]
                    }
                ];
            } else if (node.type === 'row') {
                groups = [
                    {
                        title: '列布局',
                        items: [
                            {key: 'row-column-12', label: '加整宽列'},
                            {key: 'row-column-6', label: '加半宽列'},
                            {key: 'row-column-4', label: '加三分列'}
                        ]
                    }
                ];
            } else if (node.type === 'column') {
                groups = [
                    {
                        title: '常用内容',
                        items: [
                            {key: 'column-heading-text', label: '标题正文'},
                            {key: 'column-form', label: '插表单'}
                        ]
                    },
                    {
                        title: '媒体区块',
                        items: [
                            {key: 'column-video', label: '插视频'},
                            {key: 'column-carousel', label: '插轮播'}
                        ]
                    }
                ];
            }
            if (!groups.length) {
                return '';
            }
            return '<div class="ft-page-builder__dropzone-actions">' + groups.map(function (group) {
                return '' +
                    '<div class="ft-page-builder__dropzone-group">' +
                        '<div class="ft-page-builder__dropzone-group-title">' + escapeHtml(group.title || '快捷操作') + '</div>' +
                        '<div class="ft-page-builder__dropzone-group-list">' + (group.items || []).map(function (item) {
                            return '<button type="button" class="ft-page-builder__dropzone-action" data-container-quick="' + escapeHtml(item.key) + '" data-container-path="' + escapeHtml(path) + '">' + escapeHtml(item.label) + '</button>';
                        }).join('') + '</div>' +
                    '</div>';
            }).join('') + '</div>';
        }

        function requestDeleteNode(path) {
            if (!path) {
                return;
            }
            setBuilderNotice('warning', '待删除区块：' + getNodePathLabel(path) + '。请在这里点击“确认删除”，删除后会立即从画布移除。', {
                action: 'delete-node',
                payload: {path: path},
                confirmText: '确认删除',
                cancelText: '保留区块'
            });
        }

        function buildQuickInsertMenu(path, position) {
            var items = getQuickInsertBlocks().map(function (block) {
                return '' +
                    '<button type="button" class="ft-page-builder__insert-item" data-quick-insert-type="' + escapeHtml(block.type || '') + '" data-quick-insert-path="' + escapeHtml(path || '') + '" data-quick-insert-position="' + escapeHtml(position || 'after') + '" data-quick-insert-schema="' + escapeHtml(block.schema || '') + '">' +
                        '<strong>' + escapeHtml(block.name || '') + '</strong>' +
                        '<span>' + escapeHtml(block.desc || '') + '</span>' +
                    '</button>';
            }).join('');
            return '' +
                '<div class="ft-page-builder__insert-trigger">' +
                    '<button type="button" class="ft-page-builder__insert-button" data-insert-menu-toggle="1">+</button>' +
                    '<span>' + escapeHtml(getSiblingInsertLabel(position)) + '</span>' +
                '</div>' +
                '<div class="ft-page-builder__insert-menu">' +
                    '<div class="ft-page-builder__insert-grid">' + items + '</div>' +
                '</div>';
        }

        function moveNodeRelative(sourcePath, targetPath, position) {
            if (!sourcePath || !targetPath || sourcePath === targetPath) {
                return;
            }
            var sourceParentCollectionPath = getParentCollectionPath(sourcePath);
            var targetParentCollectionPath = getParentCollectionPath(targetPath);
            if (!sourceParentCollectionPath || sourceParentCollectionPath !== targetParentCollectionPath) {
                return;
            }
            var collection = getCollectionByPath(sourceParentCollectionPath);
            if (!collection) {
                return;
            }
            var sourceIndex = getPathIndex(sourcePath);
            var targetIndex = getPathIndex(targetPath);
            if (!collection[sourceIndex] || !collection[targetIndex]) {
                return;
            }
            var adjustedTargetIndex = targetIndex;
            if (sourceIndex < targetIndex) {
                adjustedTargetIndex = targetIndex - 1;
            }
            var movingNode = collection.splice(sourceIndex, 1)[0];
            var insertIndex = position === 'before' ? adjustedTargetIndex : adjustedTargetIndex + 1;
            collection.splice(insertIndex, 0, movingNode);
            selectedPath = sourceParentCollectionPath + '.' + insertIndex;
            refreshBuilder();
        }

        function insertNodeRelative(targetPath, position, node) {
            if (!targetPath || !node) {
                return;
            }
            var parentCollectionPath = getParentCollectionPath(targetPath);
            var collection = getCollectionByPath(parentCollectionPath);
            if (!collection) {
                return;
            }
            var targetIndex = getPathIndex(targetPath);
            var insertIndex = position === 'before' ? targetIndex : targetIndex + 1;
            collection.splice(insertIndex, 0, ensureNode(node));
            selectedPath = parentCollectionPath + '.' + insertIndex;
            refreshBuilder();
        }

        function getContainerDropLabel(node) {
            if (!node) {
                return '把内容拖到这里，或直接点下面的快捷按钮';
            }
            if (node.type === 'section') {
                return '这里是区块容器，适合直接加行布局或整套分栏结构';
            }
            if (node.type === 'row') {
                return '这里是行容器，优先加列会更直观';
            }
            if (node.type === 'column') {
                return '这里是当前列，适合继续塞标题、表单、视频或轮播';
            }
            return '把内容拖到这里，或直接点下面的快捷按钮';
        }

        function getSiblingInsertLabel(position) {
            return position === 'before' ? '上方插入' : '下方插入';
        }

        function renderNode(node, path, parentCollectionPath) {
            var childKey = getChildCollectionKey(node);
            var children = childKey ? node[childKey] : [];
            var selectedClass = selectedPath === path ? ' is-selected' : '';
            var ancestorClass = selectedPath && selectedPath !== path && selectedPath.indexOf(path + '.') === 0 ? ' is-ancestor' : '';
            var parentSelectedClass = getSelectedParentNodePath() === path ? ' is-parent-of-selected' : '';
            var childHtml = '';
            var dropZoneHtml = '';
            var beforeZoneHtml = '';
            var afterZoneHtml = '';
            var trail = getNodeTrail(path);
            var depth = trail.length || 1;
            var parentItem = trail.length > 1 ? trail[trail.length - 2] : null;
            var nodeTypeClass = String(node && node.type || 'block').trim().toLowerCase().replace(/[^a-z0-9_-]/g, '-') || 'block';
            var parentTitle = parentItem ? getNodeTitle(parentItem.node) : '页面根级';
            var nodeTypeCode = getNodeTypeCode(node);
            var contextHtml = '' +
                '<div class="ft-page-builder__node-context">' +
                    '<div class="ft-page-builder__node-context-item"><strong>层级</strong>第 ' + escapeHtml(String(depth)) + ' 级</div>' +
                    '<div class="ft-page-builder__node-context-item"><strong>归属</strong>' + escapeHtml(parentTitle) + '</div>' +
                    '<div class="ft-page-builder__node-context-item"><strong>角色</strong>' + escapeHtml(getNodeRoleLabel(node)) + '</div>' +
                    '<div class="ft-page-builder__node-context-item"><strong>子级</strong>' + escapeHtml(childKey ? (getCollectionLabel(childKey) + ' / ' + children.length + ' 项') : '叶子区块') + '</div>' +
                '</div>';
            if (parentCollectionPath) {
                beforeZoneHtml = '<div class="ft-page-builder__insert-zone" data-builder-drop-zone="before" data-path="' + escapeHtml(path) + '">' + buildQuickInsertMenu(path, 'before') + '</div>';
                afterZoneHtml = '<div class="ft-page-builder__insert-zone" data-builder-drop-zone="after" data-path="' + escapeHtml(path) + '">' + buildQuickInsertMenu(path, 'after') + '</div>';
            }
            if (childKey) {
                dropZoneHtml = '' +
                    '<div class="ft-page-builder__node-scope">' +
                        '<span>' + escapeHtml(getNodeRoleLabel(node)) + '</span>' +
                        '<strong>' + escapeHtml(getCollectionLabel(childKey)) + '</strong>' +
                        '<em>当前 ' + escapeHtml(String(children.length)) + ' 项</em>' +
                    '</div>' +
                    '<div class="ft-page-builder__dropzone" data-builder-drop-zone="into" data-path="' + escapeHtml(path) + '"><div class="ft-page-builder__dropzone-tip">' + escapeHtml(getContainerDropLabel(node)) + '</div>' + buildDropzoneActions(node, path) + '</div>';
            }
            if (children.length) {
                childHtml = '<div class="ft-page-builder__node-children">' + children.map(function (child, index) {
                    return renderNode(child, path + '.' + childKey + '.' + index, path + '.' + childKey);
                }).join('') + '</div>';
            }
            var visibilitySummary = getVisibilitySummary(node);
            return '' +
                beforeZoneHtml +
                '<div class="ft-page-builder__node ft-page-builder__node--' + escapeHtml(nodeTypeClass) + ' ft-page-builder__node--depth-' + escapeHtml(String(Math.min(depth, 4))) + selectedClass + ancestorClass + parentSelectedClass + '" draggable="true" data-path="' + escapeHtml(path) + '" data-parent-collection="' + escapeHtml(parentCollectionPath) + '">' +
                    '<div class="ft-page-builder__node-header">' +
                        '<div>' +
                            '<div class="ft-page-builder__node-title-row">' +
                                '<span class="ft-page-builder__node-type-mark ft-page-builder__node-type-mark--' + escapeHtml(nodeTypeClass) + '">' + escapeHtml(nodeTypeCode) + '</span>' +
                                '<h5 class="ft-page-builder__node-title">' + escapeHtml(getNodeTitle(node)) + '</h5>' +
                            '</div>' +
                            '<div class="ft-page-builder__node-meta">' +
                                '<span class="ft-page-builder__node-badge">' + escapeHtml(node.type || 'block') + '</span>' +
                                '<span class="ft-page-builder__node-badge">' + escapeHtml(node.id || '') + '</span>' +
                                (visibilitySummary ? '<span class="ft-page-builder__node-badge">' + escapeHtml(visibilitySummary) + '</span>' : '') +
                            '</div>' +
                            '<p class="ft-page-builder__node-preview">' + escapeHtml(getNodePreview(node)) + '</p>' +
                        '</div>' +
                        '<div class="ft-page-builder__node-actions">' +
                            '<button type="button" class="btn btn-default btn-xs ft-page-builder__node-main-action" data-builder-action="select" data-path="' + escapeHtml(path) + '">编辑属性</button>' +
                            '<div class="ft-page-builder__node-menu">' +
                                '<button type="button" class="btn btn-default btn-xs" data-builder-action="menu" data-path="' + escapeHtml(path) + '">更多</button>' +
                                '<div class="ft-page-builder__node-menu-panel">' +
                                    '<button type="button" class="ft-page-builder__node-menu-item" data-builder-action="duplicate" data-path="' + escapeHtml(path) + '">复制区块</button>' +
                                    '<button type="button" class="ft-page-builder__node-menu-item" data-builder-action="up" data-path="' + escapeHtml(path) + '">上移</button>' +
                                    '<button type="button" class="ft-page-builder__node-menu-item" data-builder-action="down" data-path="' + escapeHtml(path) + '">下移</button>' +
                                    '<button type="button" class="ft-page-builder__node-menu-item is-danger" data-builder-action="delete" data-path="' + escapeHtml(path) + '">删除区块</button>' +
                                '</div>' +
                            '</div>' +
                        '</div>' +
                    '</div>' +
                    contextHtml +
                    '<div class="ft-page-builder__node-helper">' + escapeHtml(getNodeHelperText(node)) + '</div>' +
                    dropZoneHtml +
                    childHtml +
                '</div>' +
                afterZoneHtml;
        }

        function renderCanvas() {
            if (!pageBuilderCanvas) {
                return;
            }
            if (!builderState.sections.length) {
                pageBuilderCanvas.innerHTML = '<div class="ft-page-builder__canvas-empty">当前还没有区块。<br>先点左侧组件，或直接新增一个空白区块。</div>';
                return;
            }
            pageBuilderCanvas.innerHTML = '<div class="ft-page-builder__tree">' + builderState.sections.map(function (section, index) {
                return renderNode(section, 'sections.' + index, 'sections');
            }).join('') + '</div>';
        }

        function buildPreviewStyle(style, node) {
            var specialStyleMap = {
                hoverBackground: '--mx-hover-background',
                hoverColor: '--mx-hover-color',
                hoverBorderColor: '--mx-hover-border-color',
                hoverBoxShadow: '--mx-hover-box-shadow',
                hoverTransform: '--mx-hover-transform',
                ctaButtonMinHeight: '--mx-cta-button-min-height'
            };
            var currentStyleMap = {
                background: '--mx-current-background',
                color: '--mx-current-color',
                borderColor: '--mx-current-border-color',
                boxShadow: '--mx-current-box-shadow'
            };
            var sourceStyle = style && typeof style === 'object' ? style : {};
            var pairs = Object.keys(sourceStyle).map(function (key) {
                var value = sourceStyle[key];
                if (value == null || value === '') {
                    return '';
                }
                if (specialStyleMap[key]) {
                    return specialStyleMap[key] + ':' + String(value).trim();
                }
                var cssKey = key.replace(/([a-z])([A-Z])/g, '$1-$2').toLowerCase();
                var declarations = [cssKey + ':' + String(value).trim()];
                if (currentStyleMap[key]) {
                    declarations.push(currentStyleMap[key] + ':' + String(value).trim());
                }
                return declarations.join(';');
            }).filter(Boolean);
            var motion = getNodeMotion(node);
            if (motion.effect && motion.effect !== 'none') {
                pairs.push('--mx-motion-duration:' + (motion.duration || '0.7s'));
                pairs.push('--mx-motion-delay:' + (motion.delay || '0s'));
            }
            return pairs.length ? ' style="' + escapeHtml(pairs.join(';')) + '"' : '';
        }

        function buildPreviewAttributes(node, props, extraClasses) {
            var attributes = [];
            var classes = Array.isArray(extraClasses) ? extraClasses.slice() : [];
            if (props && props.className) {
                classes.push(String(props.className).trim());
            }
            var anchorId = normalizeAnchorId(props && props.anchor ? props.anchor : '');
            if (anchorId) {
                attributes.push(' id="' + escapeHtml(anchorId) + '"');
            }
            if (node && node.id) {
                attributes.push(' data-node-id="' + escapeHtml(node.id) + '"');
            }
            var motion = getNodeMotion(node);
            if (motion.effect && motion.effect !== 'none') {
                attributes.push(' data-motion-effect="' + escapeHtml(motion.effect) + '"');
                attributes.push(' data-motion-duration="' + escapeHtml(motion.duration || '0.7s') + '"');
                attributes.push(' data-motion-delay="' + escapeHtml(motion.delay || '0s') + '"');
            }
            classes = classes.filter(Boolean);
            if (classes.length) {
                attributes.push(' class="' + escapeHtml(classes.join(' ')) + '"');
            }
            return attributes.join('');
        }

        function renderPreviewChildren(children) {
            return (children || []).map(function (child) {
                return renderPreviewNode(child);
            }).join('');
        }

        function renderPreviewModelList(node, props, style) {
            var limit = Math.max(1, Math.min(parseInt(props.limit || 6, 10) || 6, 6));
            var modelName = escapeHtml(props.model || '未绑定');
            var templateName = escapeHtml(props.template || 'card');
            var items = [];
            for (var i = 1; i <= limit; i++) {
                items.push(
                    '<article class="mx-page-model-card' + (templateName === 'list' ? ' mx-page-model-card--list' : '') + '">' +
                        '<div class="mx-page-model-card__media"><img src="https://dummyimage.com/960x720/e2e8f0/0f172a&text=' + encodeURIComponent((props.title || '内容') + ' ' + i) + '" alt="' + modelName + ' 示例内容 ' + i + '"></div>' +
                        '<div class="mx-page-model-card__content">' +
                            '<div class="mx-page-model-card__meta">模型数据 / 示例 ' + i + '</div>' +
                            '<h4 class="mx-page-model-card__title"><a class="mx-page-model-card__title-link" href="#">' + modelName + ' 示例内容 ' + i + '</a></h4>' +
                            '<p class="mx-page-model-card__summary">后台实时预览先展示结构与层次，前台页面会按模型 `' + modelName + '` 查询真实标题、摘要、图片与链接。</p>' +
                            '<div class="mx-page-model-card__footer"><span class="mx-page-model-card__link is-static">内容详情</span></div>' +
                        '</div>' +
                    '</article>'
                );
            }
            return '' +
                '<section' + buildPreviewAttributes(node, props, ['mx-page-model-list', 'mx-page-model-list--' + templateName]) + buildPreviewStyle(style, node) + '>' +
                    '<div class="mx-page-model-list__head">' +
                        '<h3 class="mx-page-model-list__title">' + escapeHtml(props.title || '模型列表') + '</h3>' +
                    '</div>' +
                    '<div class="mx-page-model-list__grid mx-page-model-list__grid--' + templateName + '">' + items.join('') + '</div>' +
                '</section>';
        }

        function renderPreviewModelDetail(node, props, style) {
            var modelName = escapeHtml(props.model || '未绑定');
            return '' +
                '<section' + buildPreviewAttributes(node, props, ['mx-page-model-detail']) + buildPreviewStyle(style, node) + '>' +
                    '<div class="mx-page-model-detail__head">' +
                        '<h3 class="mx-page-model-detail__title">' + escapeHtml(props.title || '模型详情') + '</h3>' +
                    '</div>' +
                    '<article class="mx-page-detail-card mx-page-detail-card--media">' +
                        '<div class="mx-page-detail-card__media"><img src="https://dummyimage.com/960x720/e2e8f0/0f172a&text=' + encodeURIComponent((props.title || '详情') + ' Cover') + '" alt="' + modelName + ' 详情"></div>' +
                        '<div class="mx-page-detail-card__content">' +
                            '<div class="mx-page-detail-card__meta">模型详情 / 结构预览</div>' +
                            '<h2 class="mx-page-detail-card__title">' + modelName + ' 详情标题</h2>' +
                            '<p class="mx-page-detail-card__summary">后台编辑器这里先展示结构预览；前台页面会按模型 `' + modelName + '` 查询一条真实详情内容。</p>' +
                            '<div class="mx-page-detail-card__body">这里预留给正文、详情简介、图文说明等区域。后续你可以继续补链接字段、详情 ID 或详情前缀。</div>' +
                            '<div class="mx-page-detail-card__actions"><span class="mx-page-detail-card__button">继续阅读</span></div>' +
                        '</div>' +
                    '</article>' +
                '</section>';
        }

        function normalizeAnchorId(value) {
            return String(value || '')
                .trim()
                .replace(/^#+/, '')
                .replace(/\s+/g, '-')
                .replace(/[^A-Za-z0-9\-_:.]/g, '');
        }

        function isSidebarSvgSource(value) {
            return /^\s*<svg[\s>]/i.test(String(value || ''));
        }

        function isSidebarImageSource(value) {
            var source = String(value || '').trim();
            if (!source) {
                return false;
            }
            return /^(data:image\/|https?:\/\/|\/\/|\/|\.\/|\.\.\/)/i.test(source) || /\.(svg|png|jpe?g|gif|webp)(\?.*)?$/i.test(source);
        }

        function renderSidebarPreviewIcon(source, fallback) {
            var value = String(source || '').trim();
            var text = String(fallback || 'S').trim() || 'S';
            if (isSidebarSvgSource(value)) {
                return '<span class="mx-page-sidebar__icon mx-page-sidebar__icon--svg">' + value + '</span>';
            }
            if (isSidebarImageSource(value)) {
                return '<span class="mx-page-sidebar__icon mx-page-sidebar__icon--image"><img src="' + escapeHtml(value) + '" alt=""></span>';
            }
            return '<span class="mx-page-sidebar__icon">' + escapeHtml(value || text.slice(0, 1)) + '</span>';
        }

        function buildSidebarPreviewItemStyle(item) {
            var style = [];
            if (item && item.background) {
                style.push('--mx-sidebar-item-bg:' + String(item.background).trim());
            }
            if (item && item.color) {
                style.push('--mx-sidebar-item-color:' + String(item.color).trim());
            }
            if (item && item.borderColor) {
                style.push('--mx-sidebar-item-border:' + String(item.borderColor).trim());
            }
            return style.length ? ' style="' + escapeHtml(style.join(';')) + '"' : '';
        }

        function buildSidebarPreviewPanelId(node, index) {
            var base = node && node.id ? String(node.id) : 'sidebar';
            return ('preview-sidebar-panel-' + base + '-' + index).replace(/[^A-Za-z0-9\-_:.]/g, '-');
        }

        function buildPreviewQrCodeUrl(value, size) {
            var qrcodeValue = String(value || '').trim() || 'https://example.com';
            var qrcodeSize = Math.max(96, Math.min(260, parseInt(size || 160, 10) || 160));
            return 'https://api.qrserver.com/v1/create-qr-code/?size=' + qrcodeSize + 'x' + qrcodeSize + '&data=' + encodeURIComponent(qrcodeValue);
        }

        function renderPreviewSidebarRichHtml(value) {
            var html = String(value || '').trim();
            if (!html) {
                return '';
            }
            return '<div class="mx-page-sidebar__panel-rich">' + html + '</div>';
        }

        function renderPreviewSidebarPanel(item, panelId) {
            var panelType = String(item && item.panelType || 'qrcode').trim() === 'custom' ? 'custom' : 'qrcode';
            var panelTitle = String(item && item.panelTitle || item && item.text || '快捷面板').trim();
            var panelContent = String(item && item.panelContent || '').trim();
            var panelValue = String(item && item.panelValue || '').trim();
            var panelHtml = String(item && item.panelHtml || '').trim();
            var customImage = panelType === 'custom' && isSidebarImageSource(panelValue) ? panelValue : '';
            return '<div class="mx-page-sidebar__panel" data-sidebar-panel="' + escapeHtml(panelId) + '" hidden>' +
                '<button type="button" class="mx-page-sidebar__panel-close" data-sidebar-panel-close="' + escapeHtml(panelId) + '" aria-label="关闭">×</button>' +
                '<div class="mx-page-sidebar__panel-card">' +
                    '<div class="mx-page-sidebar__panel-title">' + escapeHtml(panelTitle) + '</div>' +
                    (panelType === 'qrcode'
                        ? '<div class="mx-page-sidebar__panel-qrcode"><img src="' + escapeHtml(buildPreviewQrCodeUrl(panelValue, 160)) + '" alt="' + escapeHtml(panelTitle) + '"></div>' +
                          (panelContent ? '<div class="mx-page-sidebar__panel-text">' + escapeHtml(panelContent).replace(/\n/g, '<br>') + '</div>' : '') +
                          (panelValue ? '<div class="mx-page-sidebar__panel-value">' + escapeHtml(panelValue) + '</div>' : '')
                        : renderPreviewSidebarRichHtml(panelHtml) +
                          (customImage ? '<div class="mx-page-sidebar__panel-media"><img src="' + escapeHtml(customImage) + '" alt="' + escapeHtml(panelTitle) + '"></div>' : '') +
                          (panelContent ? '<div class="mx-page-sidebar__panel-text">' + escapeHtml(panelContent).replace(/\n/g, '<br>') + '</div>' : '') +
                          (panelValue && !customImage ? '<div class="mx-page-sidebar__panel-value">' + escapeHtml(panelValue) + '</div>' : '')) +
                '</div>' +
            '</div>';
        }

        function matchPreviewVisibilityRule(visibility) {
            return matchPreviewSingleVisibilityRule(String(visibility && visibility.rule || '').trim(), visibility, '');
        }

        function matchPreviewSingleVisibilityRule(rule, visibility, prefix) {
            if (rule === 'logged_in') {
                return !!builderPreviewAuthCheck;
            }
            if (rule === 'guest') {
                return !builderPreviewAuthCheck;
            }
            if (rule === 'url_param') {
                var param = String(visibility[prefix ? (prefix + 'Param') : 'param'] || '').trim();
                var expected = String(visibility[prefix ? (prefix + 'Value') : 'value'] || '').trim();
                var query = new URLSearchParams(window.location.search || '');
                if (!param || !query.has(param)) {
                    return false;
                }
                if (!expected) {
                    return true;
                }
                return String(query.get(param) || '').trim() === expected;
            }
            if (rule === 'device') {
                return normalizeStringList(visibility[prefix ? (prefix + 'Devices') : 'devices'] || []).indexOf(previewDevice) !== -1;
            }
            return true;
        }

        function shouldPreviewNode(node) {
            var visibility = getNodeVisibility(node);
            var effect = String(visibility.effect || 'always').trim();
            if (!effect || effect === 'always') {
                return true;
            }
            var matched = matchPreviewVisibilityRule(visibility);
            var extraRule = String(visibility.extraRule || '').trim();
            if (extraRule) {
                var extraMatched = matchPreviewSingleVisibilityRule(extraRule, visibility, 'extra');
                matched = String(visibility.logic || 'all').trim() === 'any'
                    ? (matched || extraMatched)
                    : (matched && extraMatched);
            }
            if (effect === 'show') {
                return matched;
            }
            if (effect === 'hide') {
                return !matched;
            }
            return true;
        }

        function renderPreviewNavigation(node, props, style) {
            var items = Array.isArray(props.items) ? props.items : [];
            var logoType = ['text', 'image', 'svg', 'image_text'].indexOf(String(props.logoType || 'text')) !== -1 ? String(props.logoType || 'text') : 'text';
            if (!items.length) {
                items = [
                    {text: '首页', href: '#hero'},
                    {text: '产品', href: '#products', children: [{text: '产品总览', href: '#products'}, {text: '产品定价', href: '#pricing'}]},
                    {text: '联系我们', href: '#contact'}
                ];
            }
            var currentPath = String(window.location.pathname || '').replace(/\/+$/, '') || '/';
            function renderBrand() {
                var chunks = [];
                if ((logoType === 'image' || logoType === 'image_text') && props.logoImage) {
                    chunks.push('<span class="mx-page-nav__brand-logo"><img src="' + escapeHtml(props.logoImage) + '" alt="' + escapeHtml(props.logoAlt || props.title || '品牌 Logo') + '"></span>');
                }
                if ((logoType === 'svg' || logoType === 'image_text') && props.logoSvg) {
                    chunks.push('<span class="mx-page-nav__brand-logo mx-page-nav__brand-logo--svg">' + String(props.logoSvg) + '</span>');
                }
                if (logoType === 'text' || logoType === 'image_text' || !chunks.length) {
                    chunks.push('<span class="mx-page-nav__title">' + escapeHtml(props.title || '站点导航') + '</span>');
                }
                return '<a class="mx-page-nav__brand" href="' + escapeHtml(props.brandHref || '#') + '">' + chunks.join('') + '</a>';
            }
            function renderItem(item) {
                var href = String(item.href || '#');
                var targetPath = String(href.split('?')[0] || '').replace(/\/+$/, '') || '/';
                var children = Array.isArray(item.children) ? item.children : [];
                var active = currentPath === targetPath;
                var submenuHtml = '';
                if (children.length) {
                    submenuHtml = '<div class="mx-page-nav__submenu">' + children.map(function (child) {
                        var childHref = String(child.href || '#');
                        var childTargetPath = String(childHref.split('?')[0] || '').replace(/\/+$/, '') || '/';
                        var childActive = currentPath === childTargetPath;
                        if (childActive) {
                            active = true;
                        }
                        return '<a class="mx-page-nav__submenu-link' + (childActive ? ' is-active' : '') + '" href="' + escapeHtml(childHref) + '">' + escapeHtml(child.text || '子菜单') + '</a>';
                    }).join('') + '</div>';
                }
                return '<div class="mx-page-nav__item"><a class="mx-page-nav__link' + (children.length ? ' has-children' : '') + (active ? ' is-active' : '') + '" href="' + escapeHtml(href) + '">' + escapeHtml(item.text || '导航项') + '</a>' + submenuHtml + '</div>';
            }
            return '' +
                '<nav' + buildPreviewAttributes(node, props, ['mx-page-nav', 'mx-page-nav--' + (props.layout === 'vertical' ? 'vertical' : 'horizontal')]) + buildPreviewStyle(style, node) + '>' +
                    renderBrand() +
                    '<div class="mx-page-nav__list">' +
                        items.map(renderItem).join('') +
                    '</div>' +
                    (props.ctaText ? '<a class="mx-page-nav__cta" href="' + escapeHtml(props.ctaHref || '#') + '">' + escapeHtml(props.ctaText) + '</a>' : '') +
                '</nav>';
        }

        function renderPreviewSidebar(node, props, style) {
            var items = Array.isArray(props.items) ? props.items : [];
            if (!items.length) {
                items = [
                    {text: '在线咨询', href: '#contact', icon: '咨', actionType: 'link', background: '#ffffff', color: '#2563eb', borderColor: '#bfdbfe'},
                    {text: '微信咨询', href: '', icon: '微', actionType: 'panel', panelType: 'qrcode', panelTitle: '扫码咨询', panelContent: '添加顾问获取方案', panelValue: 'https://example.com/contact', background: '#0f172a', color: '#ffffff', borderColor: 'rgba(148,163,184,.22)'}
                ];
            }
            var panels = [];
            return '' +
                '<aside' + buildPreviewAttributes(node, props, ['mx-page-sidebar', 'mx-page-sidebar--' + (props.position === 'left' ? 'left' : 'right')]) + ' data-sidebar-position="' + escapeHtml(props.position === 'left' ? 'left' : 'right') + '" data-sidebar-offset="' + escapeHtml(props.offsetTop || '120px') + '"' + buildPreviewStyle(style, node) + '>' +
                    (props.title ? '<div class="mx-page-sidebar__title">' + escapeHtml(props.title) + '</div>' : '') +
                    '<div class="mx-page-sidebar__list">' +
                        items.map(function (item, index) {
                            var label = String(item.text || '快捷入口');
                            var actionType = String(item.actionType || 'link').trim() === 'panel' ? 'panel' : 'link';
                            var panelId = buildSidebarPreviewPanelId(node, index);
                            var content = '<span class="mx-page-sidebar__content"><span class="mx-page-sidebar__text">' + escapeHtml(item.text || '快捷入口') + '</span></span>';
                            var icon = renderSidebarPreviewIcon(item.icon, label);
                            var styleAttr = buildSidebarPreviewItemStyle(item);
                            if (actionType === 'panel') {
                                panels.push(renderPreviewSidebarPanel(item, panelId));
                                return '<button type="button" class="mx-page-sidebar__link mx-page-sidebar__trigger" data-sidebar-panel-trigger="' + escapeHtml(panelId) + '" aria-expanded="false"' + styleAttr + '>' + icon + content + '</button>';
                            }
                            return '<a class="mx-page-sidebar__link" href="' + escapeHtml(item.href || '#') + '" title="' + escapeHtml(label) + '"' + styleAttr + '>' + icon + content + '</a>';
                        }).join('') +
                    '</div>' +
                    (String(props.showBackTop || '1') === '1' ? '<button type="button" class="mx-page-sidebar__backtop" data-sidebar-backtop="1"><span class="mx-page-sidebar__icon">Top</span><span class="mx-page-sidebar__content"><span class="mx-page-sidebar__text">返回顶部</span></span></button>' : '') +
                    (panels.length ? '<div class="mx-page-sidebar__panels">' + panels.join('') + '</div>' : '') +
                '</aside>';
        }

        function renderPreviewQrCode(node, props, style) {
            var size = Math.max(96, Math.min(parseInt(props.size || 140, 10) || 140, 320));
            var value = String(props.value || 'https://example.com');
            var qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=' + size + 'x' + size + '&data=' + encodeURIComponent(value);
            return '' +
                '<div' + buildPreviewAttributes(node, props, ['mx-page-qrcode']) + buildPreviewStyle(style, node) + '>' +
                    '<div class="mx-page-qrcode__image"><img src="' + escapeHtml(qrUrl) + '" alt="' + escapeHtml(props.title || '二维码') + '"></div>' +
                    '<div class="mx-page-qrcode__title">' + escapeHtml(props.title || '扫码咨询') + '</div>' +
                    (props.text ? '<div class="mx-page-qrcode__text">' + escapeHtml(props.text) + '</div>' : '') +
                    (props.value ? '<div class="mx-page-qrcode__value">' + escapeHtml(props.value) + '</div>' : '') +
                '</div>';
        }

        function renderPreviewLoginBox(node, props, style) {
            var loginHref = props.loginHref || props.action || '/login';
            var loginText = props.loginText || props.buttonText || '立即登录';
            var profileText = props.profileText || '个人中心';
            var profileHref = props.profileHref || '/member';
            var avatarUrl = props.avatarUrl || builderPreviewAuthAvatar || '';
            var profileName = builderPreviewAuthName || profileText;
            var avatarMarkup = avatarUrl
                ? '<span class="mx-page-login-box__avatar"><img src="' + escapeHtml(avatarUrl) + '" alt="' + escapeHtml(profileName) + '"></span>'
                : '<span class="mx-page-login-box__avatar">' + escapeHtml((profileName || 'U').slice(0, 1).toUpperCase()) + '</span>';
            return '' +
                '<div' + buildPreviewAttributes(node, props, ['mx-page-login-box']) + buildPreviewStyle(style, node) + '>' +
                    (props.title ? '<div class="mx-page-login-box__label">' + escapeHtml(props.title) + '</div>' : '') +
                    (builderPreviewAuthCheck
                        ? '<a class="mx-page-login-box__profile" href="' + escapeHtml(profileHref) + '">' + avatarMarkup + '<span class="mx-page-login-box__profile-text">' + escapeHtml(profileName) + '</span></a>'
                        : '<a class="mx-page-login-box__button" href="' + escapeHtml(loginHref) + '">' + escapeHtml(loginText) + '</a>') +
                '</div>';
        }

        function renderPreviewCarousel(node, props, style) {
            var slides = [];
            if (props.source_type === 'model_list') {
                var limit = Math.max(1, Math.min(parseInt(props.limit || 3, 10) || 3, 6));
                for (var i = 1; i <= limit; i++) {
                    slides.push({
                        title: (props.model || 'model') + ' 示例轮播 ' + i,
                        description: '当前是模型数据轮播预览。前台会按模型 `' + (props.model || '未绑定') + '` 实时查询标题、摘要、图片和链接。',
                        image: 'https://dummyimage.com/1600x720/e2e8f0/0f172a&text=' + encodeURIComponent('Slide ' + i),
                        buttonText: props.buttonText || '查看详情',
                        buttonHref: '#'
                    });
                }
            } else {
                slides = Array.isArray(props.slides) ? props.slides : [];
            }
            if (!slides.length) {
                slides = [{
                    title: '轮播标题',
                    description: '这里会显示当前轮播首屏的标题、描述和按钮。',
                    image: 'https://dummyimage.com/1600x720/e2e8f0/0f172a&text=Slide',
                    buttonText: props.buttonText || '',
                    buttonHref: props.buttonHref || '#'
                }];
            }
            var slidesHtml = slides.map(function (slide, index) {
                var isActive = index === 0;
                return '<article class="mx-page-carousel__slide' + (isActive ? ' is-active' : '') + '" data-carousel-slide="' + index + '" aria-hidden="' + (isActive ? 'false' : 'true') + '">' +
                    '<div class="mx-page-carousel__media"><img src="' + escapeHtml(slide.image || 'https://dummyimage.com/1600x720/e2e8f0/0f172a&text=Slide') + '" alt="' + escapeHtml(slide.title || '轮播图') + '"></div>' +
                    '<div class="mx-page-carousel__overlay">' +
                        '<div class="mx-page-carousel__meta">Slide ' + escapeHtml(('0' + String(index + 1)).slice(-2)) + ' / ' + escapeHtml(String(slides.length)) + '</div>' +
                        '<h2 class="mx-page-carousel__title">' + escapeHtml(slide.title || '轮播标题') + '</h2>' +
                        (slide.description ? '<p class="mx-page-carousel__desc">' + escapeHtml(slide.description) + '</p>' : '') +
                        ((slide.buttonText || props.buttonText) ? '<a class="mx-page-carousel__button" href="' + escapeHtml(slide.buttonHref || props.buttonHref || '#') + '">' + escapeHtml(slide.buttonText || props.buttonText || '了解更多') + '</a>' : '') +
                    '</div>' +
                '</article>';
            }).join('');
            return '' +
                '<section' + buildPreviewAttributes(node, props, ['mx-page-carousel']) + ' data-carousel-autoplay="' + (String(props.autoplay || '0') === '1' ? '1' : '0') + '" data-carousel-interval="' + escapeHtml(String(props.interval || '4500')) + '"' + buildPreviewStyle(style, node) + '>' +
                    '<div class="mx-page-carousel__slides">' + slidesHtml + '</div>' +
                    '<div class="mx-page-carousel__dots">' + slides.map(function (slide, index) {
                        return '<button type="button" class="mx-page-carousel__dot' + (index === 0 ? ' is-active' : '') + '" data-carousel-dot="' + index + '" aria-pressed="' + (index === 0 ? 'true' : 'false') + '" aria-label="切换到第 ' + (index + 1) + ' 张"></button>';
                    }).join('') + '</div>' +
                '</section>';
        }

        function renderPreviewVideo(node, props, style) {
            var sourceType = props.source_type === 'mp4' ? 'mp4' : 'embed';
            var ratio = String(props.aspect_ratio || '16:9').trim();
            var match = ratio.match(/^\s*(\d+(?:\.\d+)?)\s*:\s*(\d+(?:\.\d+)?)\s*$/);
            var paddingTop = '56.25%';
            if (match) {
                paddingTop = ((parseFloat(match[2]) / parseFloat(match[1])) * 100).toFixed(4) + '%';
            }
            var frameHtml = '';
            if (sourceType === 'mp4') {
                if (props.mp4_url) {
                    frameHtml = '<video class="mx-page-video__frame" src="' + escapeHtml(props.mp4_url) + '"' + (props.poster ? (' poster="' + escapeHtml(props.poster) + '"') : '') + (String(props.controls || '1') === '0' ? '' : ' controls') + (String(props.autoplay || '0') === '1' ? ' autoplay' : '') + (String(props.muted || '0') === '1' ? ' muted' : '') + (String(props.loop || '0') === '1' ? ' loop' : '') + '></video>';
                } else {
                    frameHtml = '<div class="mx-page-empty">当前还没有 MP4 地址。</div>';
                }
            } else if (props.embed_url) {
                frameHtml = '<iframe class="mx-page-video__frame" src="' + escapeHtml(props.embed_url) + '" title="' + escapeHtml(props.title || '视频播放') + '" allowfullscreen></iframe>';
            } else {
                frameHtml = '<div class="mx-page-empty">当前还没有嵌入地址。</div>';
            }
            return '' +
                '<section' + buildPreviewAttributes(node, props, ['mx-page-video']) + buildPreviewStyle(style, node) + '>' +
                    (props.title ? '<div class="mx-page-video__title">' + escapeHtml(props.title) + '</div>' : '') +
                    '<div class="mx-page-video__viewport" style="padding-top:' + escapeHtml(paddingTop) + ';">' + frameHtml + '</div>' +
                '</section>';
        }

        function renderPreviewGallery(node, props, style) {
            var items = [];
            if (props.source_type === 'model_list') {
                var limit = Math.max(1, Math.min(parseInt(props.limit || 6, 10) || 6, 8));
                for (var i = 1; i <= limit; i++) {
                    items.push({
                        title: (props.model || 'model') + ' 图库项 ' + i,
                        image: 'https://dummyimage.com/960x720/e2e8f0/0f172a&text=' + encodeURIComponent('Gallery ' + i),
                        url: '#'
                    });
                }
            } else {
                items = Array.isArray(props.items) ? props.items : [];
            }
            if (!items.length) {
                items = [{
                    title: '图库图片',
                    image: 'https://dummyimage.com/960x720/e2e8f0/0f172a&text=Gallery',
                    url: '#'
                }];
            }
            var columns = Math.max(2, Math.min(parseInt(props.columns || 3, 10) || 3, 6));
            var gap = String(props.gap || '18px').trim();
            return '' +
                '<section' + buildPreviewAttributes(node, props, ['mx-page-gallery']) + buildPreviewStyle(style, node) + '>' +
                    ((props.title || props.subtitle) ? '<div class="mx-page-gallery__head">' +
                        (props.title ? '<h3 class="mx-page-gallery__title">' + escapeHtml(props.title) + '</h3>' : '') +
                        (props.subtitle ? '<p class="mx-page-gallery__subtitle">' + escapeHtml(props.subtitle) + '</p>' : '') +
                    '</div>' : '') +
                    '<div class="mx-page-gallery__grid" style="grid-template-columns:repeat(' + columns + ',minmax(0,1fr));' + (gap ? ('gap:' + escapeHtml(gap) + ';') : '') + '">' +
                        items.map(function (item) {
                            var card = '<div class="mx-page-gallery__media"><img src="' + escapeHtml(item.image || 'https://dummyimage.com/960x720/e2e8f0/0f172a&text=Gallery') + '" alt="' + escapeHtml(item.title || '图库图片') + '"></div>' +
                                (item.title ? '<div class="mx-page-gallery__caption">' + escapeHtml(item.title) + '</div>' : '');
                            if (item.url) {
                                card = '<a class="mx-page-gallery__card-link" href="' + escapeHtml(item.url) + '">' + card + '</a>';
                            }
                            return '<article class="mx-page-gallery__card">' + card + '</article>';
                        }).join('') +
                    '</div>' +
                '</section>';
        }

        function renderPreviewFaq(node, props, style) {
            var items = Array.isArray(props.items) ? props.items : [];
            if (!items.length) {
                items = [{question: '这里是一个常见问题？', answer: '这里显示问题答案说明。'}];
            }
            var columns = String(props.columns || '1') === '2' ? 2 : 1;
            return '' +
                '<section' + buildPreviewAttributes(node, props, ['mx-page-faq']) + ' data-faq="accordion"' + buildPreviewStyle(style, node) + '>' +
                    ((props.title || props.intro) ? '<div class="mx-page-faq__head">' +
                        (props.title ? '<h3 class="mx-page-faq__title">' + escapeHtml(props.title) + '</h3>' : '') +
                        (props.intro ? '<p class="mx-page-faq__intro">' + escapeHtml(props.intro) + '</p>' : '') +
                    '</div>' : '') +
                    '<div class="mx-page-faq__list mx-page-faq__list--cols-' + columns + '">' +
                        items.map(function (item) {
                            var isOpen = items.indexOf(item) === 0;
                            return '<article class="mx-page-faq__item' + (isOpen ? ' is-open' : '') + '" data-faq-item>' +
                                (item.question ? '<button type="button" class="mx-page-faq__question" data-faq-trigger aria-expanded="' + (isOpen ? 'true' : 'false') + '"><span>' + escapeHtml(item.question) + '</span><span class="mx-page-faq__icon" aria-hidden="true"></span></button>' : '') +
                                (item.answer ? '<div class="mx-page-faq__answer" data-faq-panel' + (isOpen ? '' : ' hidden') + '>' + escapeHtml(item.answer).replace(/\n/g, '<br>') + '</div>' : '') +
                            '</article>';
                        }).join('') +
                    '</div>' +
                '</section>';
        }

        function renderPreviewStats(node, props, style) {
            var items = Array.isArray(props.items) ? props.items : [];
            if (!items.length) {
                items = [{label: '服务客户', value: '2580', suffix: '+', description: '覆盖多行业项目'}];
            }
            var columns = Math.max(2, Math.min(parseInt(props.columns || 4, 10) || 4, 6));
            return '' +
                '<section' + buildPreviewAttributes(node, props, ['mx-page-stats']) + buildPreviewStyle(style, node) + '>' +
                    ((props.title || props.intro) ? '<div class="mx-page-stats__head">' +
                        (props.title ? '<h3 class="mx-page-stats__title">' + escapeHtml(props.title) + '</h3>' : '') +
                        (props.intro ? '<p class="mx-page-stats__intro">' + escapeHtml(props.intro) + '</p>' : '') +
                    '</div>' : '') +
                    '<div class="mx-page-stats__grid" style="grid-template-columns:repeat(' + columns + ',minmax(0,1fr));">' +
                        items.map(function (item) {
                            return '<article class="mx-page-stats__item">' +
                                (item.label ? '<div class="mx-page-stats__label">' + escapeHtml(item.label) + '</div>' : '') +
                                '<div class="mx-page-stats__value">' + escapeHtml(item.value || '0') + (item.suffix ? '<span>' + escapeHtml(item.suffix) + '</span>' : '') + '</div>' +
                                (item.description ? '<div class="mx-page-stats__desc">' + escapeHtml(item.description) + '</div>' : '') +
                            '</article>';
                        }).join('') +
                    '</div>' +
                '</section>';
        }

        function renderPreviewCta(node, props, style) {
            var align = props.align === 'center' ? 'center' : 'left';
            var actionsAlign = ['left', 'center', 'right'].indexOf(String(props.actionsAlign || '').trim()) !== -1 ? String(props.actionsAlign).trim() : (align === 'center' ? 'center' : 'left');
            var primaryVariant = ['solid', 'outline', 'ghost'].indexOf(String(props.primaryVariant || '').trim()) !== -1 ? String(props.primaryVariant).trim() : 'solid';
            var secondaryVariant = ['solid', 'outline', 'ghost'].indexOf(String(props.secondaryVariant || '').trim()) !== -1 ? String(props.secondaryVariant).trim() : 'ghost';
            return '' +
                '<section' + buildPreviewAttributes(node, props, ['mx-page-cta', 'mx-page-cta--' + align]) + buildPreviewStyle(style, node) + '>' +
                    '<div class="mx-page-cta__body">' +
                        (props.eyebrow ? '<div class="mx-page-cta__eyebrow">' + escapeHtml(props.eyebrow) + '</div>' : '') +
                        (props.title ? '<h3 class="mx-page-cta__title">' + escapeHtml(props.title) + '</h3>' : '') +
                        (props.description ? '<p class="mx-page-cta__desc">' + escapeHtml(props.description).replace(/\n/g, '<br>') + '</p>' : '') +
                        '<div class="mx-page-cta__actions mx-page-cta__actions--' + actionsAlign + '">' +
                            (props.primaryText ? '<a class="mx-page-cta__button mx-page-cta__button--' + primaryVariant + '" href="' + escapeHtml(props.primaryHref || '#') + '">' + escapeHtml(props.primaryText) + '</a>' : '') +
                            (props.secondaryText ? '<a class="mx-page-cta__button mx-page-cta__button--' + secondaryVariant + '" href="' + escapeHtml(props.secondaryHref || '#') + '">' + escapeHtml(props.secondaryText) + '</a>' : '') +
                        '</div>' +
                    '</div>' +
                '</section>';
        }

        function renderPreviewNode(node) {
            if (!node || typeof node !== 'object') {
                return '';
            }
            if (!shouldPreviewNode(node)) {
                return '';
            }
            var type = node.type || 'div';
            var props = node.props && typeof node.props === 'object' ? node.props : {};
            var style = getMergedNodeStyle(node, previewDevice);
            var childKey = getChildCollectionKey(node);
            var children = childKey ? node[childKey] || [] : [];

            if (type === 'section') {
                var sectionChildren = renderPreviewChildren(children);
                if (props.contentWidth === 'contained') {
                    sectionChildren = '<div class="mx-page-section__inner"' + getSectionInnerStyle(props) + '>' + sectionChildren + '</div>';
                }
                return '<section' + buildPreviewAttributes(node, props) + buildPreviewStyle(style, node) + '>' + sectionChildren + '</section>';
            }
            if (type === 'row') {
                return '<div' + buildPreviewAttributes(node, props, ['mx-page-row']) + buildPreviewStyle(style, node) + '>' + renderPreviewChildren(children) + '</div>';
            }
            if (type === 'column') {
                var span = getEffectiveColumnSpan(node, previewDevice);
                var blocks = Array.isArray(node.blocks) ? node.blocks : children;
                return '<div' + buildPreviewAttributes(node, props, ['mx-page-col', 'mx-page-col--' + span]) + buildPreviewStyle(style, node) + '>' + renderPreviewChildren(blocks) + '</div>';
            }
            if (type === 'heading') {
                var level = String(props.level || 'h2').toLowerCase();
                var tag = ['h1', 'h2', 'h3', 'h4', 'h5', 'h6'].indexOf(level) !== -1 ? level : 'h2';
                return '<' + tag + buildPreviewAttributes(node, props) + buildPreviewStyle(style, node) + '>' + escapeHtml(props.text || '') + '</' + tag + '>';
            }
            if (type === 'text') {
                return '<p' + buildPreviewAttributes(node, props) + buildPreviewStyle(style, node) + '>' + escapeHtml(props.text || '').replace(/\n/g, '<br>') + '</p>';
            }
            if (type === 'button') {
                var buttonAlign = ['left', 'center', 'right', 'full'].indexOf(String(props.align || '').trim()) !== -1 ? String(props.align).trim() : 'left';
                var buttonVariant = ['solid', 'outline', 'ghost'].indexOf(String(props.variant || '').trim()) !== -1 ? String(props.variant).trim() : 'solid';
                return '<a href="' + escapeHtml(props.href || '#') + '"' + buildPreviewAttributes(node, props, ['mx-page-button', 'mx-page-button--' + buttonAlign, 'mx-page-button--' + buttonVariant]) + buildPreviewStyle(style, node) + '>' + escapeHtml(props.text || '按钮') + '</a>';
            }
            if (type === 'image') {
                if (props.source_type === 'model_detail') {
                    return '<div' + buildPreviewAttributes(node, props, ['mx-page-model-detail']) + buildPreviewStyle(style, node) + '><div class="mx-page-placeholder-head"><strong>动态图片</strong><span>model: ' + escapeHtml(props.model || '未绑定') + '</span></div><article class="mx-page-detail-card"><div class="mx-page-detail-card__content"><h2 class="mx-page-detail-card__title">将从模型详情读取首图</h2><p class="mx-page-detail-card__summary">图片字段：' + escapeHtml(props.image_field || '自动识别') + '，详情 ID：' + escapeHtml(String(props.record_id || '最新一条')) + '。</p></div></article></div>';
                }
                var previewImageSrc = normalizeBuilderPreviewImageSrc(props.src);
                if (!previewImageSrc) {
                    return '';
                }
                var imageAlign = ['left', 'center', 'right'].indexOf(String(props.align || '').trim()) !== -1 ? String(props.align).trim() : 'left';
                return '<img src="' + escapeHtml(previewImageSrc) + '" alt="' + escapeHtml(props.alt || '') + '"' + buildPreviewAttributes(node, props, ['mx-page-image', 'mx-page-image--' + imageAlign]) + buildPreviewStyle(style, node) + '>';
            }
            if (type === 'carousel') {
                return renderPreviewCarousel(node, props, style);
            }
            if (type === 'video') {
                return renderPreviewVideo(node, props, style);
            }
            if (type === 'gallery') {
                return renderPreviewGallery(node, props, style);
            }
            if (type === 'faq') {
                return renderPreviewFaq(node, props, style);
            }
            if (type === 'stats') {
                return renderPreviewStats(node, props, style);
            }
            if (type === 'cta') {
                return renderPreviewCta(node, props, style);
            }
            if (type === 'divider') {
                return '<hr' + buildPreviewAttributes(node, props) + buildPreviewStyle(style, node) + '>';
            }
            if (type === 'model_list') {
                return renderPreviewModelList(node, props, style);
            }
            if (type === 'model_detail') {
                return renderPreviewModelDetail(node, props, style);
            }
            if (type === 'html') {
                return String(props.html || '');
            }
            if (type === 'navigation') {
                return renderPreviewNavigation(node, props, style);
            }
            if (type === 'sidebar') {
                return renderPreviewSidebar(node, props, style);
            }
            if (type === 'qrcode') {
                return renderPreviewQrCode(node, props, style);
            }
            if (type === 'login_box') {
                return renderPreviewLoginBox(node, props, style);
            }
            return '<div' + buildPreviewAttributes(node, props) + buildPreviewStyle(style, node) + '>' + renderPreviewChildren(children) + '</div>';
        }

        function renderLivePreview() {
            if (!pageBuilderLivePreview) {
                return;
            }
            if (!builderState.sections.length) {
                pageBuilderLivePreview.innerHTML = '<div class="ft-page-builder__live-empty">当前还没有布局区块。<br>先从左侧添加一个 `section` 或其它组件开始。</div>';
                if (pageBuilderLivePreviewNote) {
                    pageBuilderLivePreviewNote.textContent = getDeviceLabel(previewDevice) + ' 预览会跟着当前 layout_schema 同步；空白时不会渲染任何前台结构。';
                }
                return;
            }
            pageBuilderLivePreview.innerHTML = '<div class="ft-page-builder__live-preview-stage"><div class="ft-page-builder__live-preview-viewport ft-page-builder__live-preview-viewport--' + previewDevice + '"><div class="ft-page-builder-preview is-device-' + previewDevice + '"' + buildThemeStyleAttribute(builderState.theme) + '><div class="mx-page-root"' + buildThemeDataAttribute(builderState.theme) + '>' + builderState.sections.map(function (section) {
                return renderPreviewNode(section);
            }).join('') + '</div></div></div></div>';
            if (window.MxPageRuntime && typeof window.MxPageRuntime.init === 'function') {
                window.MxPageRuntime.init(pageBuilderLivePreview);
            }
            if (pageBuilderLivePreviewNote) {
                pageBuilderLivePreviewNote.textContent = '当前是 ' + getDeviceLabel(previewDevice) + ' 预览。`model_list`、`model_detail` 在编辑器里先展示结构占位，真正的动态数据以前台页面为准。';
            }
            if (pageBuilderDeviceMeta) {
                pageBuilderDeviceMeta.textContent = getDeviceLabel(previewDevice) + ' / ' + getDeviceSizeText(previewDevice);
            }
            if (pageBuilderDeviceSwitch) {
                [].slice.call(pageBuilderDeviceSwitch.querySelectorAll('[data-builder-device]')).forEach(function (button) {
                    var isActive = button.getAttribute('data-builder-device') === previewDevice;
                    button.classList.toggle('btn-primary', isActive);
                    button.classList.toggle('btn-default', !isActive);
                });
            }
        }

        function renderCanvasStatus() {
            if (!pageBuilderCanvasStatus) {
                return;
            }
            if (!builderState.sections.length) {
                pageBuilderCanvasStatus.innerHTML = '当前画布还是空的。先添加一个 <code>section</code>，再往里面拖 <code>row</code>、<code>column</code> 或普通内容区块。当前预览设备：<code>' + escapeHtml(getDeviceLabel(previewDevice)) + '</code>。';
                return;
            }
            if (!selectedPath) {
                pageBuilderCanvasStatus.innerHTML = '当前未选中区块。可以直接拖拽现有卡片调整层级，也可以先点一个区块再从左侧添加组件。当前预览设备：<code>' + escapeHtml(getDeviceLabel(previewDevice)) + '</code>。';
                return;
            }
            var node = getNodeByPath(selectedPath);
            if (!node) {
                pageBuilderCanvasStatus.innerHTML = '当前未选中区块。可以直接拖拽现有卡片调整层级，也可以先点一个区块再从左侧添加组件。当前预览设备：<code>' + escapeHtml(getDeviceLabel(previewDevice)) + '</code>。';
                return;
            }
            var childKey = getChildCollectionKey(node);
            var visibilitySummary = getVisibilitySummary(node);
            if (childKey) {
                pageBuilderCanvasStatus.innerHTML = '已选中 <code>' + escapeHtml(node.type || 'block') + '</code>，这是一个容器区块。左侧新增组件会优先插入这里，也可以直接点容器里的快捷按钮加行、加列或插入常用区块。' + (visibilitySummary ? (' 当前条件：<code>' + escapeHtml(visibilitySummary) + '</code>。') : '') + '当前预览设备：<code>' + escapeHtml(getDeviceLabel(previewDevice)) + '</code>。';
                return;
            }
            pageBuilderCanvasStatus.innerHTML = '已选中 <code>' + escapeHtml(node.type || 'block') + '</code>。左侧新增组件会插入到它后面；如果想放进容器，可点目标容器里的快捷按钮或拖到投放区。' + (visibilitySummary ? (' 当前条件：<code>' + escapeHtml(visibilitySummary) + '</code>。') : '') + '当前预览设备：<code>' + escapeHtml(getDeviceLabel(previewDevice)) + '</code>。';
        }

        function renderInspector() {
            var node = getNodeByPath(selectedPath);
            if (!node) {
                selectedPath = '';
                if (inspectorEmpty) {
                    inspectorEmpty.style.display = 'block';
                }
                if (inspectorForm) {
                    inspectorForm.style.display = 'none';
                }
                if (builderContainerConfig) {
                    builderContainerConfig.style.display = 'none';
                }
                if (builderVisualConfig) {
                    builderVisualConfig.style.display = 'none';
                }
                if (builderQuickPresetWrap) {
                    builderQuickPresetWrap.style.display = 'none';
                }
                if (builderStylePresetWrap) {
                    builderStylePresetWrap.style.display = 'none';
                }
                if (builderCommonStyleConfig) {
                    builderCommonStyleConfig.style.display = 'none';
                }
                renderInspectorStickyActions(null);
                setInspectorTab(activeInspectorTab);
                renderInspectorSummary(null);
                renderReusableBlocks();
                return;
            }
            inspectorEmpty.style.display = 'none';
            inspectorForm.style.display = 'block';
            builderNodePath.value = selectedPath;
            builderNodeType.value = node.type || '';
            builderNodeId.value = node.id || '';
            builderNodeProps.value = JSON.stringify(node.props || {}, null, 4);
            builderNodeStyle.value = JSON.stringify(node.style || {}, null, 4);
            builderTabletStyle.value = JSON.stringify(getResponsiveStyle(node, 'tablet'), null, 4);
            builderMobileStyle.value = JSON.stringify(getResponsiveStyle(node, 'mobile'), null, 4);
            fillResponsiveVisualFields('tablet', getResponsiveStyle(node, 'tablet'));
            fillResponsiveVisualFields('mobile', getResponsiveStyle(node, 'mobile'));
            builderNodePropsHint.textContent = '复杂字段再改这里的 JSON；常用内容优先用上面的可视化控件。当前容器键：' + (getChildCollectionKey(node) || '无');
            builderNodeStyleHint.textContent = '复杂样式再改这里；常用边距、颜色和圆角尽量走可视化操作。';
            if (builderVisibilityConfig) {
                builderVisibilityConfig.style.display = 'block';
                var visibility = getNodeVisibility(node);
                builderVisibilityEffect.value = visibility.effect || 'always';
                builderVisibilityRule.value = visibility.rule || 'logged_in';
                builderVisibilityLogic.value = visibility.logic || 'all';
                builderVisibilityExtraRule.value = visibility.extraRule || '';
                builderVisibilityParam.value = visibility.param || '';
                builderVisibilityValue.value = visibility.value || '';
                builderVisibilityDevices.value = normalizeStringList(visibility.devices || []).join(',');
                builderVisibilityExtraParam.value = visibility.extraParam || '';
                builderVisibilityExtraValue.value = visibility.extraValue || '';
                builderVisibilityExtraDevices.value = normalizeStringList(visibility.extraDevices || []).join(',');
                syncVisibilityRuleFields();
                if (builderVisibilityHint) {
                    builderVisibilityHint.classList.remove('ft-page-builder__field-error');
                    builderVisibilityHint.textContent = visibility.effect && visibility.effect !== 'always'
                        ? ('当前条件：命中规则后会' + (visibility.effect === 'show' ? '显示' : '隐藏') + '当前区块。' + (visibility.extraRule ? (' 条件关系：' + (visibility.logic === 'any' ? '任一满足' : '同时满足') + '。') : ''))
                        : '默认始终显示。条件命中后，可选择“显示”或“隐藏”当前区块。';
                }
            }
            if (builderVisualConfig) {
                toggleElement(builderVisualConfig, ['section', 'heading', 'text', 'button', 'image', 'divider', 'html', 'carousel', 'video', 'model_list', 'model_detail', 'gallery', 'faq', 'stats', 'cta', 'navigation', 'sidebar', 'qrcode', 'login_box'].indexOf(node.type) !== -1);
                toggleElement(builderSectionVisualWrap, node.type === 'section');
                toggleElement(builderHeadingVisualWrap, node.type === 'heading');
                toggleElement(builderTextVisualWrap, node.type === 'text');
                toggleElement(builderButtonVisualWrap, node.type === 'button');
                toggleElement(builderImageVisualWrap, node.type === 'image');
                toggleElement(builderDividerVisualWrap, node.type === 'divider');
                toggleElement(builderHtmlVisualWrap, node.type === 'html');
                toggleElement(builderCarouselVisualWrap, node.type === 'carousel');
                toggleElement(builderVideoVisualWrap, node.type === 'video');
                toggleElement(builderModelListVisualWrap, node.type === 'model_list');
                toggleElement(builderModelDetailVisualWrap, node.type === 'model_detail');
                toggleElement(builderGalleryVisualWrap, node.type === 'gallery');
                toggleElement(builderFaqVisualWrap, node.type === 'faq');
                toggleElement(builderStatsVisualWrap, node.type === 'stats');
                toggleElement(builderCtaVisualWrap, node.type === 'cta');
                toggleElement(builderNavigationVisualWrap, node.type === 'navigation');
                toggleElement(builderSidebarVisualWrap, node.type === 'sidebar');
                toggleElement(builderQrCodeVisualWrap, node.type === 'qrcode');
                toggleElement(builderLoginVisualWrap, node.type === 'login_box');
                if (builderVisualHint) {
                    builderVisualHint.classList.remove('ft-page-builder__field-error');
                    builderVisualHint.textContent = '当前区块类型：' + (node.type || 'block') + '。这里先放最常改的字段，复杂结构再回到 JSON。';
                }
                renderQuickPresets(node);
                renderStylePresets(node);
                toggleElement(builderCommonStyleConfig, node.type !== 'divider');
                fillCommonStyleFields(node.style || {});
                if (builderSectionContentWidth) {
                    builderSectionContentWidth.value = node.props && node.props.contentWidth === 'contained' ? 'contained' : 'full';
                }
                if (builderSectionInnerWidth) {
                    builderSectionInnerWidth.value = node.props && node.props.innerWidth ? node.props.innerWidth : '1180px';
                }
                if (builderSectionBackground) {
                    builderSectionBackground.value = node.style && node.style.background ? node.style.background : '';
                    syncColorInput(builderSectionBackground, builderSectionBackgroundPicker, '#ffffff');
                }
                if (builderHeadingText) {
                    builderHeadingText.value = node.props && node.props.text ? node.props.text : '';
                }
                if (builderHeadingLevel) {
                    builderHeadingLevel.value = node.props && node.props.level ? node.props.level : 'h2';
                }
                if (builderHeadingAlign) {
                    builderHeadingAlign.value = node.style && node.style.textAlign ? node.style.textAlign : '';
                }
                if (builderHeadingColor) {
                    builderHeadingColor.value = node.style && node.style.color ? node.style.color : '';
                    syncColorInput(builderHeadingColor, builderHeadingColorPicker, '#0f172a');
                }
                if (builderHeadingFontSize) {
                    builderHeadingFontSize.value = node.style && node.style.fontSize ? node.style.fontSize : '';
                }
                if (builderTextContent) {
                    builderTextContent.value = node.props && node.props.text ? node.props.text : '';
                }
                if (builderTextAlign) {
                    builderTextAlign.value = node.style && node.style.textAlign ? node.style.textAlign : '';
                }
                if (builderTextColor) {
                    builderTextColor.value = node.style && node.style.color ? node.style.color : '';
                    syncColorInput(builderTextColor, builderTextColorPicker, '#475569');
                }
                if (builderTextFontSize) {
                    builderTextFontSize.value = node.style && node.style.fontSize ? node.style.fontSize : '';
                }
                if (builderTextLineHeight) {
                    builderTextLineHeight.value = node.style && node.style.lineHeight ? node.style.lineHeight : '';
                }
                if (builderButtonText) {
                    builderButtonText.value = node.props && node.props.text ? node.props.text : '';
                }
                if (builderButtonHref) {
                    builderButtonHref.value = node.props && node.props.href ? node.props.href : '';
                }
                if (builderButtonTarget) {
                    builderButtonTarget.value = node.props && node.props.target ? node.props.target : '';
                }
                if (builderButtonAlign) {
                    builderButtonAlign.value = node.props && node.props.align ? node.props.align : 'left';
                }
                if (builderButtonVariant) {
                    builderButtonVariant.value = node.props && node.props.variant ? node.props.variant : 'solid';
                }
                if (builderButtonBackground) {
                    builderButtonBackground.value = node.style && node.style.background ? node.style.background : '';
                    syncColorInput(builderButtonBackground, builderButtonBackgroundPicker, '#2563eb');
                }
                if (builderButtonColor) {
                    builderButtonColor.value = node.style && node.style.color ? node.style.color : '';
                    syncColorInput(builderButtonColor, builderButtonColorPicker, '#ffffff');
                }
                if (builderButtonRadius) {
                    builderButtonRadius.value = node.style && node.style.borderRadius ? node.style.borderRadius : '';
                }
                if (builderButtonMinHeight) {
                    builderButtonMinHeight.value = node.style && node.style.minHeight ? node.style.minHeight : '';
                }
                if (builderButtonBorderColor) {
                    builderButtonBorderColor.value = node.style && node.style.borderColor ? node.style.borderColor : '';
                    syncEnhancedColorTextInput(builderButtonBorderColor, '#2563eb');
                }
                if (builderButtonPadding) {
                    builderButtonPadding.value = node.style && node.style.padding ? node.style.padding : '';
                }
                if (builderButtonHoverBackground) {
                    builderButtonHoverBackground.value = node.style && node.style.hoverBackground ? node.style.hoverBackground : '';
                }
                if (builderButtonHoverColor) {
                    builderButtonHoverColor.value = node.style && node.style.hoverColor ? node.style.hoverColor : '';
                    syncEnhancedColorTextInput(builderButtonHoverColor, '#ffffff');
                }
                if (builderButtonHoverBorderColor) {
                    builderButtonHoverBorderColor.value = node.style && node.style.hoverBorderColor ? node.style.hoverBorderColor : '';
                    syncEnhancedColorTextInput(builderButtonHoverBorderColor, '#1d4ed8');
                }
                if (builderButtonHoverShadow) {
                    builderButtonHoverShadow.value = node.style && node.style.hoverBoxShadow ? node.style.hoverBoxShadow : '';
                }
                if (builderImageSrc) {
                    builderImageSrc.value = node.props && node.props.src ? node.props.src : '';
                }
                if (builderImageSourceType) {
                    builderImageSourceType.value = node.props && node.props.source_type === 'model_detail' ? 'model_detail' : 'manual';
                }
                if (builderImageModel) {
                    builderImageModel.value = node.props && node.props.model ? node.props.model : '';
                }
                if (builderImageRecordId) {
                    builderImageRecordId.value = node.props && node.props.record_id ? node.props.record_id : '';
                }
                if (builderImageField) {
                    builderImageField.value = node.props && node.props.image_field ? node.props.image_field : '';
                }
                if (builderImageAlt) {
                    builderImageAlt.value = node.props && node.props.alt ? node.props.alt : '';
                }
                if (builderImageWidth) {
                    builderImageWidth.value = node.style && node.style.width ? node.style.width : '';
                }
                if (builderImageRadius) {
                    builderImageRadius.value = node.style && node.style.borderRadius ? node.style.borderRadius : '';
                }
                if (builderImageObjectFit) {
                    builderImageObjectFit.value = node.style && node.style.objectFit ? node.style.objectFit : '';
                }
                if (builderImageAlign) {
                    builderImageAlign.value = node.props && node.props.align ? node.props.align : 'left';
                }
                if (builderImageMinHeight) {
                    builderImageMinHeight.value = node.style && node.style.minHeight ? node.style.minHeight : '';
                }
                if (builderImageHoverScale) {
                    builderImageHoverScale.value = node.style && node.style.hoverTransform ? node.style.hoverTransform : '';
                }
                if (builderImageHoverShadow) {
                    builderImageHoverShadow.value = node.style && node.style.hoverBoxShadow ? node.style.hoverBoxShadow : '';
                }
                syncImageSourceFields();
                if (builderDividerColor) {
                    builderDividerColor.value = node.style && (node.style.borderTopColor || node.style.borderColor) ? (node.style.borderTopColor || node.style.borderColor) : '#e5e7eb';
                    syncEnhancedColorTextInput(builderDividerColor, '#e5e7eb');
                }
                if (builderDividerThickness) {
                    builderDividerThickness.value = node.style && node.style.borderTopWidth ? node.style.borderTopWidth : '1px';
                }
                if (builderDividerStyle) {
                    builderDividerStyle.value = node.style && node.style.borderTopStyle ? node.style.borderTopStyle : 'solid';
                }
                if (builderDividerWidth) {
                    builderDividerWidth.value = node.style && node.style.width ? node.style.width : '100%';
                }
                if (builderHtmlContent) {
                    builderHtmlContent.value = node.props && node.props.html ? node.props.html : '';
                }
                if (builderCarouselSourceType) {
                    builderCarouselSourceType.value = node.props && node.props.source_type === 'model_list' ? 'model_list' : 'manual';
                }
                if (builderCarouselAutoplay) {
                    builderCarouselAutoplay.value = node.props && String(node.props.autoplay || '') === '0' ? '0' : '1';
                }
                if (builderCarouselInterval) {
                    builderCarouselInterval.value = node.props && node.props.interval ? node.props.interval : '5000';
                }
                if (builderCarouselButtonText) {
                    builderCarouselButtonText.value = node.props && node.props.buttonText ? node.props.buttonText : '';
                }
                if (builderCarouselButtonHref) {
                    builderCarouselButtonHref.value = node.props && node.props.buttonHref ? node.props.buttonHref : '';
                }
                if (builderCarouselModel) {
                    builderCarouselModel.value = node.props && node.props.model ? node.props.model : '';
                }
                if (builderCarouselLimit) {
                    builderCarouselLimit.value = node.props && node.props.limit ? node.props.limit : 3;
                }
                if (builderCarouselTitleField) {
                    builderCarouselTitleField.value = node.props && node.props.title_field ? node.props.title_field : '';
                }
                if (builderCarouselSummaryField) {
                    builderCarouselSummaryField.value = node.props && node.props.summary_field ? node.props.summary_field : '';
                }
                if (builderCarouselImageField) {
                    builderCarouselImageField.value = node.props && node.props.image_field ? node.props.image_field : '';
                }
                if (builderCarouselUrlField) {
                    builderCarouselUrlField.value = node.props && node.props.url_field ? node.props.url_field : '';
                }
                if (builderCarouselDetailPrefix) {
                    builderCarouselDetailPrefix.value = node.props && node.props.detail_prefix ? node.props.detail_prefix : '';
                }
                if (builderCarouselSlides) {
                    builderCarouselSlides.value = serializeCarouselSlides(node.props && node.props.slides ? node.props.slides : []);
                }
                renderSimpleListEditor('carousel', node.props && node.props.slides ? node.props.slides : []);
                syncCarouselSourceFields();
                if (builderVideoSourceType) {
                    builderVideoSourceType.value = node.props && node.props.source_type === 'mp4' ? 'mp4' : 'embed';
                }
                if (builderVideoTitle) {
                    builderVideoTitle.value = node.props && node.props.title ? node.props.title : '';
                }
                if (builderVideoEmbedUrl) {
                    builderVideoEmbedUrl.value = node.props && node.props.embed_url ? node.props.embed_url : '';
                }
                if (builderVideoMp4Url) {
                    builderVideoMp4Url.value = node.props && node.props.mp4_url ? node.props.mp4_url : '';
                }
                if (builderVideoPoster) {
                    builderVideoPoster.value = node.props && node.props.poster ? node.props.poster : '';
                }
                if (builderVideoAspectRatio) {
                    builderVideoAspectRatio.value = node.props && node.props.aspect_ratio ? node.props.aspect_ratio : '16:9';
                }
                if (builderVideoControls) {
                    builderVideoControls.value = node.props && String(node.props.controls || '') === '0' ? '0' : '1';
                }
                if (builderVideoAutoplay) {
                    builderVideoAutoplay.value = node.props && String(node.props.autoplay || '') === '1' ? '1' : '0';
                }
                if (builderVideoMuted) {
                    builderVideoMuted.value = node.props && String(node.props.muted || '') === '1' ? '1' : '0';
                }
                if (builderVideoLoop) {
                    builderVideoLoop.value = node.props && String(node.props.loop || '') === '1' ? '1' : '0';
                }
                syncVideoSourceFields();
                if (builderModelListTitle) {
                    builderModelListTitle.value = node.props && node.props.title ? node.props.title : '';
                }
                if (builderModelListTemplate) {
                    builderModelListTemplate.value = node.props && node.props.template ? node.props.template : 'card';
                }
                if (builderModelListModel) {
                    builderModelListModel.value = node.props && node.props.model ? node.props.model : '';
                }
                if (builderModelListLimit) {
                    builderModelListLimit.value = node.props && node.props.limit ? node.props.limit : 6;
                }
                if (builderModelListOrderBy) {
                    builderModelListOrderBy.value = node.props && node.props.order_by ? node.props.order_by : '';
                }
                if (builderModelListOrderDirection) {
                    builderModelListOrderDirection.value = node.props && String(node.props.order_direction || '').toLowerCase() === 'asc' ? 'asc' : 'desc';
                }
                if (builderModelListTitleField) {
                    builderModelListTitleField.value = node.props && node.props.title_field ? node.props.title_field : '';
                }
                if (builderModelListSummaryField) {
                    builderModelListSummaryField.value = node.props && node.props.summary_field ? node.props.summary_field : '';
                }
                if (builderModelListImageField) {
                    builderModelListImageField.value = node.props && node.props.image_field ? node.props.image_field : '';
                }
                if (builderModelListDateField) {
                    builderModelListDateField.value = node.props && node.props.date_field ? node.props.date_field : '';
                }
                if (builderModelListUrlField) {
                    builderModelListUrlField.value = node.props && node.props.url_field ? node.props.url_field : '';
                }
                if (builderModelListDetailPrefix) {
                    builderModelListDetailPrefix.value = node.props && node.props.detail_prefix ? node.props.detail_prefix : '';
                }
                if (builderModelDetailTitle) {
                    builderModelDetailTitle.value = node.props && node.props.title ? node.props.title : '';
                }
                if (builderModelDetailTemplate) {
                    builderModelDetailTemplate.value = node.props && node.props.template ? node.props.template : 'detail';
                }
                if (builderModelDetailModel) {
                    builderModelDetailModel.value = node.props && node.props.model ? node.props.model : '';
                }
                if (builderModelDetailRecordId) {
                    builderModelDetailRecordId.value = node.props && node.props.record_id ? node.props.record_id : '';
                }
                if (builderModelDetailTitleField) {
                    builderModelDetailTitleField.value = node.props && node.props.title_field ? node.props.title_field : '';
                }
                if (builderModelDetailSummaryField) {
                    builderModelDetailSummaryField.value = node.props && node.props.summary_field ? node.props.summary_field : '';
                }
                if (builderModelDetailContentField) {
                    builderModelDetailContentField.value = node.props && node.props.content_field ? node.props.content_field : '';
                }
                if (builderModelDetailImageField) {
                    builderModelDetailImageField.value = node.props && node.props.image_field ? node.props.image_field : '';
                }
                if (builderModelDetailDateField) {
                    builderModelDetailDateField.value = node.props && node.props.date_field ? node.props.date_field : '';
                }
                if (builderModelDetailUrlField) {
                    builderModelDetailUrlField.value = node.props && node.props.url_field ? node.props.url_field : '';
                }
                if (builderModelDetailDetailPrefix) {
                    builderModelDetailDetailPrefix.value = node.props && node.props.detail_prefix ? node.props.detail_prefix : '';
                }
                if (builderGalleryTitle) {
                    builderGalleryTitle.value = node.props && node.props.title ? node.props.title : '';
                }
                if (builderGallerySubtitle) {
                    builderGallerySubtitle.value = node.props && node.props.subtitle ? node.props.subtitle : '';
                }
                if (builderGallerySourceType) {
                    builderGallerySourceType.value = node.props && node.props.source_type === 'model_list' ? 'model_list' : 'manual';
                }
                if (builderGalleryColumns) {
                    builderGalleryColumns.value = node.props && node.props.columns ? node.props.columns : 3;
                }
                if (builderGalleryGap) {
                    builderGalleryGap.value = node.props && node.props.gap ? node.props.gap : '18px';
                }
                if (builderGalleryModel) {
                    builderGalleryModel.value = node.props && node.props.model ? node.props.model : '';
                }
                if (builderGalleryLimit) {
                    builderGalleryLimit.value = node.props && node.props.limit ? node.props.limit : 6;
                }
                if (builderGalleryTitleField) {
                    builderGalleryTitleField.value = node.props && node.props.title_field ? node.props.title_field : '';
                }
                if (builderGalleryImageField) {
                    builderGalleryImageField.value = node.props && node.props.image_field ? node.props.image_field : '';
                }
                if (builderGalleryUrlField) {
                    builderGalleryUrlField.value = node.props && node.props.url_field ? node.props.url_field : '';
                }
                if (builderGalleryDetailPrefix) {
                    builderGalleryDetailPrefix.value = node.props && node.props.detail_prefix ? node.props.detail_prefix : '';
                }
                if (builderGalleryItems) {
                    builderGalleryItems.value = serializeGalleryItems(node.props && node.props.items ? node.props.items : []);
                }
                renderSimpleListEditor('gallery', node.props && node.props.items ? node.props.items : []);
                syncGallerySourceFields();
                if (builderFaqTitle) {
                    builderFaqTitle.value = node.props && node.props.title ? node.props.title : '';
                }
                if (builderFaqIntro) {
                    builderFaqIntro.value = node.props && node.props.intro ? node.props.intro : '';
                }
                if (builderFaqColumns) {
                    builderFaqColumns.value = node.props && String(node.props.columns || '') === '2' ? '2' : '1';
                }
                if (builderFaqItems) {
                    builderFaqItems.value = serializeFaqItems(node.props && node.props.items ? node.props.items : []);
                }
                renderSimpleListEditor('faq', node.props && node.props.items ? node.props.items : []);
                if (builderStatsTitle) {
                    builderStatsTitle.value = node.props && node.props.title ? node.props.title : '';
                }
                if (builderStatsIntro) {
                    builderStatsIntro.value = node.props && node.props.intro ? node.props.intro : '';
                }
                if (builderStatsColumns) {
                    builderStatsColumns.value = node.props && node.props.columns ? node.props.columns : 4;
                }
                if (builderStatsItems) {
                    builderStatsItems.value = serializeStatsItems(node.props && node.props.items ? node.props.items : []);
                }
                renderSimpleListEditor('stats', node.props && node.props.items ? node.props.items : []);
                if (builderCtaEyebrow) {
                    builderCtaEyebrow.value = node.props && node.props.eyebrow ? node.props.eyebrow : '';
                }
                if (builderCtaTitle) {
                    builderCtaTitle.value = node.props && node.props.title ? node.props.title : '';
                }
                if (builderCtaDescription) {
                    builderCtaDescription.value = node.props && node.props.description ? node.props.description : '';
                }
                if (builderCtaPrimaryText) {
                    builderCtaPrimaryText.value = node.props && node.props.primaryText ? node.props.primaryText : '';
                }
                if (builderCtaPrimaryHref) {
                    builderCtaPrimaryHref.value = node.props && node.props.primaryHref ? node.props.primaryHref : '';
                }
                if (builderCtaSecondaryText) {
                    builderCtaSecondaryText.value = node.props && node.props.secondaryText ? node.props.secondaryText : '';
                }
                if (builderCtaSecondaryHref) {
                    builderCtaSecondaryHref.value = node.props && node.props.secondaryHref ? node.props.secondaryHref : '';
                }
                if (builderCtaAlign) {
                    builderCtaAlign.value = node.props && node.props.align === 'center' ? 'center' : 'left';
                }
                if (builderCtaActionsAlign) {
                    builderCtaActionsAlign.value = node.props && node.props.actionsAlign ? node.props.actionsAlign : (node.props && node.props.align === 'center' ? 'center' : 'left');
                }
                if (builderCtaPrimaryVariant) {
                    builderCtaPrimaryVariant.value = node.props && node.props.primaryVariant ? node.props.primaryVariant : 'solid';
                }
                if (builderCtaSecondaryVariant) {
                    builderCtaSecondaryVariant.value = node.props && node.props.secondaryVariant ? node.props.secondaryVariant : 'ghost';
                }
                if (builderCtaButtonMinHeight) {
                    builderCtaButtonMinHeight.value = node.style && node.style.ctaButtonMinHeight ? node.style.ctaButtonMinHeight : '';
                }
                if (builderCtaHoverBackground) {
                    builderCtaHoverBackground.value = node.style && node.style.hoverBackground ? node.style.hoverBackground : '';
                }
                if (builderCtaHoverColor) {
                    builderCtaHoverColor.value = node.style && node.style.hoverColor ? node.style.hoverColor : '';
                    syncEnhancedColorTextInput(builderCtaHoverColor, '#0f172a');
                }
                if (builderCtaHoverBorderColor) {
                    builderCtaHoverBorderColor.value = node.style && node.style.hoverBorderColor ? node.style.hoverBorderColor : '';
                    syncEnhancedColorTextInput(builderCtaHoverBorderColor, '#ffffff');
                }
                if (builderCtaHoverShadow) {
                    builderCtaHoverShadow.value = node.style && node.style.hoverBoxShadow ? node.style.hoverBoxShadow : '';
                }
                if (builderNavigationTitle) {
                    builderNavigationTitle.value = node.props && node.props.title ? node.props.title : '';
                }
                if (builderNavigationLogoType) {
                    builderNavigationLogoType.value = node.props && ['text', 'image', 'svg', 'image_text'].indexOf(node.props.logoType) !== -1 ? node.props.logoType : 'text';
                }
                if (builderNavigationLayout) {
                    builderNavigationLayout.value = node.props && node.props.layout === 'vertical' ? 'vertical' : 'horizontal';
                }
                if (builderNavigationBrandHref) {
                    builderNavigationBrandHref.value = node.props && node.props.brandHref ? node.props.brandHref : '/';
                }
                if (builderNavigationCtaText) {
                    builderNavigationCtaText.value = node.props && node.props.ctaText ? node.props.ctaText : '';
                }
                if (builderNavigationCtaHref) {
                    builderNavigationCtaHref.value = node.props && node.props.ctaHref ? node.props.ctaHref : '';
                }
                if (builderNavigationLogoImage) {
                    builderNavigationLogoImage.value = node.props && node.props.logoImage ? node.props.logoImage : '';
                }
                if (builderNavigationLogoSvg) {
                    builderNavigationLogoSvg.value = node.props && node.props.logoSvg ? node.props.logoSvg : '';
                }
                if (builderNavigationItems) {
                    builderNavigationItems.value = serializeLinkItems(node.props && node.props.items ? node.props.items : []);
                }
                renderNavigationItemsEditor(node.props && node.props.items ? node.props.items : []);
                if (builderSidebarTitle) {
                    builderSidebarTitle.value = node.props && node.props.title ? node.props.title : '快捷入口';
                }
                if (builderSidebarPosition) {
                    builderSidebarPosition.value = node.props && node.props.position === 'left' ? 'left' : 'right';
                }
                if (builderSidebarOffsetTop) {
                    builderSidebarOffsetTop.value = node.props && node.props.offsetTop ? node.props.offsetTop : '120px';
                }
                if (builderSidebarShowBackTop) {
                    builderSidebarShowBackTop.value = node.props && String(node.props.showBackTop || '') === '0' ? '0' : '1';
                }
                if (builderSidebarItems) {
                    builderSidebarItems.value = serializeSidebarItems(node.props && node.props.items ? node.props.items : []);
                }
                renderSimpleListEditor('sidebar', node.props && node.props.items ? node.props.items : []);
                if (builderQrCodeTitle) {
                    builderQrCodeTitle.value = node.props && node.props.title ? node.props.title : '扫码咨询';
                }
                if (builderQrCodeText) {
                    builderQrCodeText.value = node.props && node.props.text ? node.props.text : '';
                }
                if (builderQrCodeValue) {
                    builderQrCodeValue.value = node.props && node.props.value ? node.props.value : '';
                }
                if (builderQrCodeSize) {
                    builderQrCodeSize.value = node.props && node.props.size ? node.props.size : 140;
                }
                if (builderLoginTitle) {
                    builderLoginTitle.value = node.props && node.props.title ? node.props.title : '账号入口';
                }
                if (builderLoginAction) {
                    builderLoginAction.value = node.props && (node.props.loginHref || node.props.action) ? (node.props.loginHref || node.props.action) : '/login';
                }
                if (builderLoginButtonText) {
                    builderLoginButtonText.value = node.props && (node.props.loginText || node.props.buttonText) ? (node.props.loginText || node.props.buttonText) : '立即登录';
                }
                if (builderLoginProfileText) {
                    builderLoginProfileText.value = node.props && node.props.profileText ? node.props.profileText : '个人中心';
                }
                if (builderLoginProfileHref) {
                    builderLoginProfileHref.value = node.props && node.props.profileHref ? node.props.profileHref : '/member';
                }
                if (builderLoginAvatarUrl) {
                    builderLoginAvatarUrl.value = node.props && node.props.avatarUrl ? node.props.avatarUrl : '';
                }
                if (builderAnchorId) {
                    builderAnchorId.value = node.props && node.props.anchor ? node.props.anchor : '';
                }
                fillSpacingFields('padding', node.style && node.style.padding ? node.style.padding : '');
                fillSpacingFields('margin', node.style && node.style.margin ? node.style.margin : '');
                renderImagePreview(node.props && node.props.src ? node.props.src : '');
                setFieldHelpState(builderImageUploadHint, '支持直接上传一张图片并自动回填地址；如果当前页面未绑定模型，会自动回退到第一个可用模型完成上传。', false);
            }
            if (builderAnimationConfig) {
                var motion = getNodeMotion(node);
                builderAnimationEffect.value = motion.effect ? motion.effect : 'none';
                builderAnimationDuration.value = motion.duration ? motion.duration : '';
                builderAnimationDelay.value = motion.delay ? motion.delay : '';
                if (builderAnimationHint) {
                    builderAnimationHint.textContent = motion.effect && motion.effect !== 'none'
                        ? ('当前动效：' + motion.effect + '，区块进入视口后会按设定时长和延迟执行。')
                        : '当前未启用入场动效。';
                    builderAnimationHint.classList.remove('ft-page-builder__field-error');
                }
            }
            renderInspectorStickyActions(node);
            renderInspectorSummary(node);
            renderReusableBlocks();
            setFieldHelpState(builderReusableHint, '复用区块保存在当前浏览器里，适合重复搭建同类页面。当前选中：' + buildReusableTitle(node), false);
            if (builderResponsiveHint) {
                builderResponsiveHint.textContent = '当前预览设备：' + getDeviceLabel(previewDevice) + '。桌面端继续使用上面的 style；这里单独补平板和手机的响应式样式。';
            }
            if (builderTabletSpanWrap) {
                builderTabletSpanWrap.style.display = node.type === 'column' ? 'block' : 'none';
            }
            if (builderMobileSpanWrap) {
                builderMobileSpanWrap.style.display = node.type === 'column' ? 'block' : 'none';
            }
            if (builderTabletSpan) {
                builderTabletSpan.value = node.responsive && node.responsive.tablet && node.responsive.tablet.span ? node.responsive.tablet.span : '';
            }
            if (builderMobileSpan) {
                builderMobileSpan.value = node.responsive && node.responsive.mobile && node.responsive.mobile.span ? node.responsive.mobile.span : '';
            }
            if (builderContainerConfig) {
                if (isContainerNode(node)) {
                    builderContainerConfig.style.display = 'block';
                    builderContainerClass.value = node.props && node.props.className ? node.props.className : '';
                    builderContainerBackground.value = node.style && node.style.background ? node.style.background : '';
                    builderContainerPadding.value = node.style && node.style.padding ? node.style.padding : '';
                    builderContainerMargin.value = node.style && node.style.margin ? node.style.margin : '';
                    builderRowGapWrap.style.display = node.type === 'row' ? 'block' : 'none';
                    builderColumnSpanWrap.style.display = node.type === 'column' ? 'block' : 'none';
                    if (builderRowGap) {
                        builderRowGap.value = node.style && node.style.gap ? node.style.gap : '';
                    }
                    if (builderColumnSpan) {
                        builderColumnSpan.value = node.props && node.props.span ? node.props.span : 12;
                    }
                } else {
                    builderContainerConfig.style.display = 'none';
                    builderRowGapWrap.style.display = 'none';
                    builderColumnSpanWrap.style.display = 'none';
                }
            }
            setInspectorTab(activeInspectorTab);
        }

        function refreshBuilder() {
            syncSchemaTextarea();
            renderCatalogFilters();
            renderCatalogQuickPanel();
            renderCatalogList();
            renderThemePanel();
            renderCanvas();
            renderSelectionBar();
            renderCanvasStatus();
            renderLivePreview();
            renderInspector();
            refreshPageFormState();
        }

        function createNodeFromCatalog(type, schemaText) {
            if (schemaText) {
                try {
                    return ensureNode(JSON.parse(schemaText));
                } catch (error) {
                }
            }
            var block = blockCatalog.find(function (item) {
                return item.type === type;
            });
            if (!block) {
                return ensureNode({type: type});
            }
            return ensureNode(JSON.parse(block.schema));
        }

        function applyPresetLayout(presetKey) {
            var presetMap = createLayoutPresetMap();
            var presetNodes = presetMap[presetKey];
            if (!presetNodes || !presetNodes.length) {
                return false;
            }
            builderState.sections = presetNodes.map(function (node) {
                return ensureNode(node);
            });
            selectedPath = builderState.sections.length ? 'sections.0' : '';
            refreshBuilder();
            return true;
        }

        function insertPresetLayout(presetKey, kind) {
            var presetMap = createLayoutPresetMap();
            var presetNodes = presetMap[presetKey];
            if (!presetNodes || !presetNodes.length) {
                return;
            }
            kind = kind || 'preset';
            if (!builderState.sections.length) {
                if (applyPresetLayout(presetKey)) {
                    setBuilderNotice('success', kind === 'template'
                        ? '整页模板已导入当前页面。'
                        : '区块模板已导入当前页面。');
                }
                return;
            }
            setBuilderNotice(
                'warning',
                kind === 'template'
                    ? '导入整页模板会清空当前页面内容，是否继续？'
                    : '导入区块模板会清空当前页面内容并替换为当前区块模板，是否继续？',
                {
                    confirmText: '清空并导入',
                    cancelText: '暂不导入',
                    action: 'replace-layout-preset',
                    payload: {
                        presetKey: presetKey,
                        kind: kind
                    }
                }
            );
        }

        function insertNode(node) {
            if (node.type === 'section') {
                builderState.sections.push(node);
                selectedPath = 'sections.' + (builderState.sections.length - 1);
                refreshBuilder();
                return;
            }

            if (!builderState.sections.length) {
                builderState.sections.push(ensureNode({
                    type: 'section',
                    style: {
                        padding: '48px 0',
                        background: '#ffffff'
                    },
                    children: []
                }));
            }

            if (!selectedPath) {
                builderState.sections[0].children.push(node);
                selectedPath = 'sections.0.children.' + (builderState.sections[0].children.length - 1);
                refreshBuilder();
                return;
            }

            var selectedNode = getNodeByPath(selectedPath);
            var childKey = getChildCollectionKey(selectedNode);
            if (childKey) {
                selectedNode[childKey].push(node);
                selectedPath = selectedPath + '.' + childKey + '.' + (selectedNode[childKey].length - 1);
                refreshBuilder();
                return;
            }

            var parentCollectionPath = getParentCollectionPath(selectedPath);
            var parentCollection = getCollectionByPath(parentCollectionPath);
            var selectedIndex = getPathIndex(selectedPath);
            if (!parentCollection) {
                return;
            }
            parentCollection.splice(selectedIndex + 1, 0, node);
            selectedPath = parentCollectionPath + '.' + (selectedIndex + 1);
            refreshBuilder();
        }

        function deleteNode(path) {
            var parentCollectionPath = getParentCollectionPath(path);
            var parentCollection = getCollectionByPath(parentCollectionPath);
            var index = getPathIndex(path);
            if (!parentCollection) {
                return;
            }
            parentCollection.splice(index, 1);
            if (selectedPath === path || selectedPath.indexOf(path + '.') === 0) {
                selectedPath = '';
            }
            refreshBuilder();
        }

        function duplicateNode(path) {
            var parentCollectionPath = getParentCollectionPath(path);
            var parentCollection = getCollectionByPath(parentCollectionPath);
            var index = getPathIndex(path);
            if (!parentCollection || !parentCollection[index]) {
                return;
            }
            var duplicated = ensureNode(deepClone(parentCollection[index]));
            parentCollection.splice(index + 1, 0, duplicated);
            selectedPath = parentCollectionPath + '.' + (index + 1);
            refreshBuilder();
        }

        function moveNodeIntoContainer(sourcePath, targetPath) {
            if (!sourcePath || !targetPath || isSameOrChildPath(sourcePath, targetPath)) {
                return;
            }
            var sourceParentCollectionPath = getParentCollectionPath(sourcePath);
            var sourceParentCollection = getCollectionByPath(sourceParentCollectionPath);
            var sourceIndex = getPathIndex(sourcePath);
            if (!sourceParentCollection || !sourceParentCollection[sourceIndex]) {
                return;
            }

            var adjustedTargetPath = adjustSiblingPathAfterRemoval(targetPath, sourcePath) || targetPath;
            var movingNode = sourceParentCollection.splice(sourceIndex, 1)[0];
            var targetNode = getNodeByPath(adjustedTargetPath);
            var childKey = getChildCollectionKey(targetNode);
            if (!targetNode || !childKey) {
                sourceParentCollection.splice(sourceIndex, 0, movingNode);
                return;
            }
            if (!Array.isArray(targetNode[childKey])) {
                targetNode[childKey] = [];
            }
            targetNode[childKey].push(movingNode);
            selectedPath = adjustedTargetPath + '.' + childKey + '.' + (targetNode[childKey].length - 1);
            refreshBuilder();
        }

        function moveNode(path, direction) {
            var parentCollectionPath = getParentCollectionPath(path);
            var parentCollection = getCollectionByPath(parentCollectionPath);
            var index = getPathIndex(path);
            if (!parentCollection) {
                return;
            }
            var nextIndex = direction === 'up' ? index - 1 : index + 1;
            if (nextIndex < 0 || nextIndex >= parentCollection.length) {
                return;
            }
            var temp = parentCollection[index];
            parentCollection[index] = parentCollection[nextIndex];
            parentCollection[nextIndex] = temp;
            selectedPath = parentCollectionPath + '.' + nextIndex;
            refreshBuilder();
        }

        function parseJsonField(text, fieldName) {
            if (text.trim() === '') {
                return {};
            }
            var parsed = JSON.parse(text);
            if (!parsed || typeof parsed !== 'object' || Array.isArray(parsed)) {
                throw new Error(fieldName + ' 必须是 JSON 对象');
            }
            return parsed;
        }

        function applyResponsiveStyle(device) {
            var node = getNodeByPath(selectedPath);
            var field = device === 'tablet' ? builderTabletStyle : builderMobileStyle;
            if (!node || !field) {
                return;
            }
            try {
                var config = ensureResponsiveConfig(node, device);
                config.style = parseJsonField(field.value, getDeviceLabel(device) + ' Style');
                cleanupResponsiveConfig(node);
                if (builderResponsiveHint) {
                    builderResponsiveHint.classList.remove('ft-page-builder__field-error');
                    builderResponsiveHint.textContent = '当前预览设备：' + getDeviceLabel(previewDevice) + '。桌面端继续使用上面的 style；这里单独补平板和手机的响应式样式。';
                }
                refreshBuilder();
            } catch (error) {
                if (builderResponsiveHint) {
                    builderResponsiveHint.classList.add('ft-page-builder__field-error');
                    builderResponsiveHint.textContent = error.message;
                }
            }
        }

        function applyResponsiveSpan(device) {
            var node = getNodeByPath(selectedPath);
            var field = device === 'tablet' ? builderTabletSpan : builderMobileSpan;
            if (!node || node.type !== 'column' || !field) {
                return;
            }
            var config = ensureResponsiveConfig(node, device);
            var span = parseInt(field.value || '', 10);
            if (span >= 1 && span <= 12) {
                config.span = span;
            } else {
                delete config.span;
            }
            cleanupResponsiveConfig(node);
            refreshBuilder();
        }

        function applyVisualConfig() {
            var node = getNodeByPath(selectedPath);
            if (!node) {
                return;
            }
            setNodeNestedValue(node, 'props', 'anchor', builderAnchorId ? builderAnchorId.value.trim() : '');
            if (node.type === 'section') {
                setNodeNestedValue(node, 'props', 'contentWidth', builderSectionContentWidth.value === 'contained' ? 'contained' : null);
                setNodeNestedValue(node, 'props', 'innerWidth', builderSectionContentWidth.value === 'contained' ? builderSectionInnerWidth.value.trim() : null);
                setNodeNestedValue(node, 'style', 'background', builderSectionBackground.value.trim());
            } else if (node.type === 'heading') {
                setNodeNestedValue(node, 'props', 'text', builderHeadingText.value.trim());
                setNodeNestedValue(node, 'props', 'level', builderHeadingLevel.value.trim() || 'h2');
                setNodeNestedValue(node, 'style', 'textAlign', builderHeadingAlign.value.trim());
                setNodeNestedValue(node, 'style', 'color', builderHeadingColor.value.trim());
                setNodeNestedValue(node, 'style', 'fontSize', builderHeadingFontSize.value.trim());
            } else if (node.type === 'text') {
                setNodeNestedValue(node, 'props', 'text', builderTextContent.value);
                setNodeNestedValue(node, 'style', 'textAlign', builderTextAlign.value.trim());
                setNodeNestedValue(node, 'style', 'color', builderTextColor.value.trim());
                setNodeNestedValue(node, 'style', 'fontSize', builderTextFontSize.value.trim());
                setNodeNestedValue(node, 'style', 'lineHeight', builderTextLineHeight.value.trim());
            } else if (node.type === 'button') {
                var buttonVariant = builderButtonVariant.value.trim() || 'solid';
                setNodeNestedValue(node, 'props', 'text', builderButtonText.value.trim());
                setNodeNestedValue(node, 'props', 'href', builderButtonHref.value.trim());
                setNodeNestedValue(node, 'props', 'target', builderButtonTarget.value.trim());
                setNodeNestedValue(node, 'props', 'align', builderButtonAlign.value.trim() || 'left');
                setNodeNestedValue(node, 'props', 'variant', buttonVariant);
                setNodeNestedValue(node, 'style', 'background', buttonVariant === 'outline' ? 'transparent' : builderButtonBackground.value.trim());
                setNodeNestedValue(node, 'style', 'color', builderButtonColor.value.trim());
                setNodeNestedValue(node, 'style', 'borderRadius', builderButtonRadius.value.trim());
                setNodeNestedValue(node, 'style', 'minHeight', builderButtonMinHeight.value.trim());
                setNodeNestedValue(node, 'style', 'borderColor', builderButtonBorderColor.value.trim());
                fillSpacingFields('padding', builderButtonPadding.value.trim());
                setNodeNestedValue(node, 'style', 'padding', builderButtonPadding.value.trim());
                setNodeNestedValue(node, 'style', 'hoverBackground', builderButtonHoverBackground.value.trim());
                setNodeNestedValue(node, 'style', 'hoverColor', builderButtonHoverColor.value.trim());
                setNodeNestedValue(node, 'style', 'hoverBorderColor', builderButtonHoverBorderColor.value.trim());
                setNodeNestedValue(node, 'style', 'hoverBoxShadow', builderButtonHoverShadow.value.trim());
                if (builderCommonMinHeight) {
                    builderCommonMinHeight.value = builderButtonMinHeight.value.trim();
                }
                if (builderCommonRadius) {
                    builderCommonRadius.value = builderButtonRadius.value.trim();
                }
                if (builderCommonBorderColor) {
                    builderCommonBorderColor.value = builderButtonBorderColor.value.trim();
                }
            } else if (node.type === 'image') {
                setNodeNestedValue(node, 'props', 'source_type', builderImageSourceType.value.trim() === 'model_detail' ? 'model_detail' : 'manual');
                setNodeNestedValue(node, 'props', 'model', builderImageSourceType.value.trim() === 'model_detail' ? builderImageModel.value.trim() : null);
                setNodeNestedValue(node, 'props', 'record_id', builderImageSourceType.value.trim() === 'model_detail' ? (builderImageRecordId.value ? String(builderImageRecordId.value).trim() : '') : null);
                setNodeNestedValue(node, 'props', 'image_field', builderImageSourceType.value.trim() === 'model_detail' ? builderImageField.value.trim() : null);
                setNodeNestedValue(node, 'props', 'src', builderImageSrc.value.trim());
                setNodeNestedValue(node, 'props', 'alt', builderImageAlt.value.trim());
                setNodeNestedValue(node, 'props', 'align', builderImageAlign.value.trim() || 'left');
                setNodeNestedValue(node, 'style', 'width', builderImageWidth.value.trim());
                setNodeNestedValue(node, 'style', 'borderRadius', builderImageRadius.value.trim());
                setNodeNestedValue(node, 'style', 'minHeight', builderImageMinHeight.value.trim());
                setNodeNestedValue(node, 'style', 'objectFit', builderImageObjectFit.value.trim());
                setNodeNestedValue(node, 'style', 'hoverTransform', builderImageHoverScale.value.trim());
                setNodeNestedValue(node, 'style', 'hoverBoxShadow', builderImageHoverShadow.value.trim());
                if (builderCommonWidth) {
                    builderCommonWidth.value = builderImageWidth.value.trim();
                }
                if (builderCommonMinHeight) {
                    builderCommonMinHeight.value = builderImageMinHeight.value.trim();
                }
                if (builderCommonRadius) {
                    builderCommonRadius.value = builderImageRadius.value.trim();
                }
                renderImagePreview(builderImageSrc.value.trim());
            } else if (node.type === 'divider') {
                setNodeNestedValue(node, 'style', 'border', '0');
                setNodeNestedValue(node, 'style', 'borderTopColor', builderDividerColor.value.trim());
                setNodeNestedValue(node, 'style', 'borderColor', builderDividerColor.value.trim());
                setNodeNestedValue(node, 'style', 'borderTopWidth', builderDividerThickness.value.trim() || '1px');
                setNodeNestedValue(node, 'style', 'borderTopStyle', builderDividerStyle.value.trim() || 'solid');
                setNodeNestedValue(node, 'style', 'width', builderDividerWidth.value.trim() || '100%');
            } else if (node.type === 'html') {
                setNodeNestedValue(node, 'props', 'html', builderHtmlContent.value);
            } else if (node.type === 'carousel') {
                setNodeNestedValue(node, 'props', 'source_type', builderCarouselSourceType.value.trim() === 'model_list' ? 'model_list' : 'manual');
                setNodeNestedValue(node, 'props', 'autoplay', builderCarouselAutoplay.value.trim() === '0' ? '0' : '1');
                setNodeNestedValue(node, 'props', 'interval', builderCarouselInterval.value ? String(builderCarouselInterval.value).trim() : '');
                setNodeNestedValue(node, 'props', 'buttonText', builderCarouselButtonText.value.trim());
                setNodeNestedValue(node, 'props', 'buttonHref', builderCarouselButtonHref.value.trim());
                if (builderCarouselSourceType.value.trim() === 'model_list') {
                    setNodeNestedValue(node, 'props', 'model', builderCarouselModel.value.trim());
                    setNodeNestedValue(node, 'props', 'limit', builderCarouselLimit.value ? String(builderCarouselLimit.value).trim() : '');
                    setNodeNestedValue(node, 'props', 'title_field', builderCarouselTitleField.value.trim());
                    setNodeNestedValue(node, 'props', 'summary_field', builderCarouselSummaryField.value.trim());
                    setNodeNestedValue(node, 'props', 'image_field', builderCarouselImageField.value.trim());
                    setNodeNestedValue(node, 'props', 'url_field', builderCarouselUrlField.value.trim());
                    setNodeNestedValue(node, 'props', 'detail_prefix', builderCarouselDetailPrefix.value.trim());
                    delete node.props.slides;
                } else {
                    setNodeNestedValue(node, 'props', 'slides', parseCarouselSlides(builderCarouselSlides.value));
                    delete node.props.model;
                    delete node.props.limit;
                    delete node.props.title_field;
                    delete node.props.summary_field;
                    delete node.props.image_field;
                    delete node.props.url_field;
                    delete node.props.detail_prefix;
                }
            } else if (node.type === 'video') {
                setNodeNestedValue(node, 'props', 'source_type', builderVideoSourceType.value.trim() === 'mp4' ? 'mp4' : 'embed');
                setNodeNestedValue(node, 'props', 'title', builderVideoTitle.value.trim());
                setNodeNestedValue(node, 'props', 'embed_url', builderVideoSourceType.value.trim() === 'embed' ? builderVideoEmbedUrl.value.trim() : null);
                setNodeNestedValue(node, 'props', 'mp4_url', builderVideoSourceType.value.trim() === 'mp4' ? builderVideoMp4Url.value.trim() : null);
                setNodeNestedValue(node, 'props', 'poster', builderVideoPoster.value.trim());
                setNodeNestedValue(node, 'props', 'aspect_ratio', builderVideoAspectRatio.value.trim() || '16:9');
                setNodeNestedValue(node, 'props', 'controls', builderVideoControls.value.trim() === '0' ? '0' : '1');
                setNodeNestedValue(node, 'props', 'autoplay', builderVideoAutoplay.value.trim() === '1' ? '1' : '0');
                setNodeNestedValue(node, 'props', 'muted', builderVideoMuted.value.trim() === '1' ? '1' : '0');
                setNodeNestedValue(node, 'props', 'loop', builderVideoLoop.value.trim() === '1' ? '1' : '0');
            } else if (node.type === 'model_list') {
                setNodeNestedValue(node, 'props', 'title', builderModelListTitle.value.trim());
                setNodeNestedValue(node, 'props', 'template', builderModelListTemplate.value.trim() || 'card');
                setNodeNestedValue(node, 'props', 'model', builderModelListModel.value.trim());
                setNodeNestedValue(node, 'props', 'limit', builderModelListLimit.value ? String(builderModelListLimit.value).trim() : '');
                setNodeNestedValue(node, 'props', 'order_by', builderModelListOrderBy.value.trim());
                setNodeNestedValue(node, 'props', 'order_direction', builderModelListOrderDirection.value.trim() === 'asc' ? 'asc' : 'desc');
                setNodeNestedValue(node, 'props', 'title_field', builderModelListTitleField.value.trim());
                setNodeNestedValue(node, 'props', 'summary_field', builderModelListSummaryField.value.trim());
                setNodeNestedValue(node, 'props', 'image_field', builderModelListImageField.value.trim());
                setNodeNestedValue(node, 'props', 'date_field', builderModelListDateField.value.trim());
                setNodeNestedValue(node, 'props', 'url_field', builderModelListUrlField.value.trim());
                setNodeNestedValue(node, 'props', 'detail_prefix', builderModelListDetailPrefix.value.trim());
            } else if (node.type === 'model_detail') {
                setNodeNestedValue(node, 'props', 'title', builderModelDetailTitle.value.trim());
                setNodeNestedValue(node, 'props', 'template', builderModelDetailTemplate.value.trim() || 'detail');
                setNodeNestedValue(node, 'props', 'model', builderModelDetailModel.value.trim());
                setNodeNestedValue(node, 'props', 'record_id', builderModelDetailRecordId.value ? String(builderModelDetailRecordId.value).trim() : '');
                setNodeNestedValue(node, 'props', 'title_field', builderModelDetailTitleField.value.trim());
                setNodeNestedValue(node, 'props', 'summary_field', builderModelDetailSummaryField.value.trim());
                setNodeNestedValue(node, 'props', 'content_field', builderModelDetailContentField.value.trim());
                setNodeNestedValue(node, 'props', 'image_field', builderModelDetailImageField.value.trim());
                setNodeNestedValue(node, 'props', 'date_field', builderModelDetailDateField.value.trim());
                setNodeNestedValue(node, 'props', 'url_field', builderModelDetailUrlField.value.trim());
                setNodeNestedValue(node, 'props', 'detail_prefix', builderModelDetailDetailPrefix.value.trim());
            } else if (node.type === 'gallery') {
                setNodeNestedValue(node, 'props', 'title', builderGalleryTitle.value.trim());
                setNodeNestedValue(node, 'props', 'subtitle', builderGallerySubtitle.value.trim());
                setNodeNestedValue(node, 'props', 'source_type', builderGallerySourceType.value.trim() === 'model_list' ? 'model_list' : 'manual');
                setNodeNestedValue(node, 'props', 'columns', builderGalleryColumns.value ? String(builderGalleryColumns.value).trim() : '3');
                setNodeNestedValue(node, 'props', 'gap', builderGalleryGap.value.trim() || '18px');
                if (builderGallerySourceType.value.trim() === 'model_list') {
                    setNodeNestedValue(node, 'props', 'model', builderGalleryModel.value.trim());
                    setNodeNestedValue(node, 'props', 'limit', builderGalleryLimit.value ? String(builderGalleryLimit.value).trim() : '');
                    setNodeNestedValue(node, 'props', 'title_field', builderGalleryTitleField.value.trim());
                    setNodeNestedValue(node, 'props', 'image_field', builderGalleryImageField.value.trim());
                    setNodeNestedValue(node, 'props', 'url_field', builderGalleryUrlField.value.trim());
                    setNodeNestedValue(node, 'props', 'detail_prefix', builderGalleryDetailPrefix.value.trim());
                    delete node.props.items;
                } else {
                    setNodeNestedValue(node, 'props', 'items', parseGalleryItems(builderGalleryItems.value));
                    delete node.props.model;
                    delete node.props.limit;
                    delete node.props.title_field;
                    delete node.props.image_field;
                    delete node.props.url_field;
                    delete node.props.detail_prefix;
                }
            } else if (node.type === 'faq') {
                setNodeNestedValue(node, 'props', 'title', builderFaqTitle.value.trim());
                setNodeNestedValue(node, 'props', 'intro', builderFaqIntro.value.trim());
                setNodeNestedValue(node, 'props', 'columns', builderFaqColumns.value.trim() === '2' ? '2' : '1');
                setNodeNestedValue(node, 'props', 'items', parseFaqItems(builderFaqItems.value));
            } else if (node.type === 'stats') {
                setNodeNestedValue(node, 'props', 'title', builderStatsTitle.value.trim());
                setNodeNestedValue(node, 'props', 'intro', builderStatsIntro.value.trim());
                setNodeNestedValue(node, 'props', 'columns', builderStatsColumns.value ? String(builderStatsColumns.value).trim() : '4');
                setNodeNestedValue(node, 'props', 'items', parseStatsItems(builderStatsItems.value));
            } else if (node.type === 'cta') {
                setNodeNestedValue(node, 'props', 'eyebrow', builderCtaEyebrow.value.trim());
                setNodeNestedValue(node, 'props', 'title', builderCtaTitle.value.trim());
                setNodeNestedValue(node, 'props', 'description', builderCtaDescription.value.trim());
                setNodeNestedValue(node, 'props', 'primaryText', builderCtaPrimaryText.value.trim());
                setNodeNestedValue(node, 'props', 'primaryHref', builderCtaPrimaryHref.value.trim());
                setNodeNestedValue(node, 'props', 'secondaryText', builderCtaSecondaryText.value.trim());
                setNodeNestedValue(node, 'props', 'secondaryHref', builderCtaSecondaryHref.value.trim());
                setNodeNestedValue(node, 'props', 'align', builderCtaAlign.value.trim() === 'center' ? 'center' : 'left');
                setNodeNestedValue(node, 'props', 'actionsAlign', builderCtaActionsAlign.value.trim() || '');
                setNodeNestedValue(node, 'props', 'primaryVariant', builderCtaPrimaryVariant.value.trim() || 'solid');
                setNodeNestedValue(node, 'props', 'secondaryVariant', builderCtaSecondaryVariant.value.trim() || 'ghost');
                setNodeNestedValue(node, 'style', 'ctaButtonMinHeight', builderCtaButtonMinHeight.value.trim());
                setNodeNestedValue(node, 'style', 'hoverBackground', builderCtaHoverBackground.value.trim());
                setNodeNestedValue(node, 'style', 'hoverColor', builderCtaHoverColor.value.trim());
                setNodeNestedValue(node, 'style', 'hoverBorderColor', builderCtaHoverBorderColor.value.trim());
                setNodeNestedValue(node, 'style', 'hoverBoxShadow', builderCtaHoverShadow.value.trim());
            } else if (node.type === 'navigation') {
                setNodeNestedValue(node, 'props', 'title', builderNavigationTitle.value.trim());
                setNodeNestedValue(node, 'props', 'logoType', builderNavigationLogoType.value.trim() || 'text');
                setNodeNestedValue(node, 'props', 'layout', builderNavigationLayout.value.trim() === 'vertical' ? 'vertical' : 'horizontal');
                setNodeNestedValue(node, 'props', 'brandHref', builderNavigationBrandHref.value.trim());
                setNodeNestedValue(node, 'props', 'ctaText', builderNavigationCtaText.value.trim());
                setNodeNestedValue(node, 'props', 'ctaHref', builderNavigationCtaHref.value.trim());
                setNodeNestedValue(node, 'props', 'logoImage', builderNavigationLogoImage.value.trim());
                setNodeNestedValue(node, 'props', 'logoSvg', builderNavigationLogoSvg.value.trim());
                setNodeNestedValue(node, 'props', 'items', parseLinkItems(builderNavigationItems.value));
            } else if (node.type === 'sidebar') {
                setNodeNestedValue(node, 'props', 'title', builderSidebarTitle.value.trim());
                setNodeNestedValue(node, 'props', 'position', builderSidebarPosition.value.trim() === 'left' ? 'left' : 'right');
                setNodeNestedValue(node, 'props', 'offsetTop', builderSidebarOffsetTop.value.trim());
                setNodeNestedValue(node, 'props', 'showBackTop', builderSidebarShowBackTop.value.trim() === '0' ? '0' : '1');
                setNodeNestedValue(node, 'props', 'items', parseSidebarItems(builderSidebarItems.value));
            } else if (node.type === 'qrcode') {
                setNodeNestedValue(node, 'props', 'title', builderQrCodeTitle.value.trim());
                setNodeNestedValue(node, 'props', 'text', builderQrCodeText.value.trim());
                setNodeNestedValue(node, 'props', 'value', builderQrCodeValue.value.trim());
                setNodeNestedValue(node, 'props', 'size', builderQrCodeSize.value ? String(builderQrCodeSize.value).trim() : '');
            } else if (node.type === 'login_box') {
                setNodeNestedValue(node, 'props', 'title', builderLoginTitle.value.trim());
                setNodeNestedValue(node, 'props', 'loginHref', builderLoginAction.value.trim());
                setNodeNestedValue(node, 'props', 'loginText', builderLoginButtonText.value.trim());
                setNodeNestedValue(node, 'props', 'profileText', builderLoginProfileText.value.trim());
                setNodeNestedValue(node, 'props', 'profileHref', builderLoginProfileHref.value.trim());
                setNodeNestedValue(node, 'props', 'avatarUrl', builderLoginAvatarUrl.value.trim());
                delete node.props.subtitle;
                delete node.props.action;
                delete node.props.buttonText;
                delete node.props.accountPlaceholder;
                delete node.props.passwordPlaceholder;
                delete node.props.forgotText;
                delete node.props.forgotHref;
                delete node.props.registerText;
                delete node.props.registerHref;
            }
            applyVisibilityConfig(node);
            applyMotionConfig(node);
            applyCommonStyleConfig(node);
            setNodeNestedValue(node, 'style', 'padding', readSpacingFields('padding'));
            setNodeNestedValue(node, 'style', 'margin', readSpacingFields('margin'));
            if (node.type === 'button' && builderButtonPadding) {
                builderButtonPadding.value = readSpacingFields('padding');
            }
            refreshBuilder();
        }

        function applyInspectorField(field) {
            var node = getNodeByPath(selectedPath);
            if (!node) {
                return;
            }
            if (field === 'id') {
                node.id = builderNodeId.value.trim() || uniqueId(node.type || 'node');
                refreshBuilder();
                return;
            }
            try {
                if (field === 'props') {
                    node.props = parseJsonField(builderNodeProps.value, 'Props');
                    builderNodePropsHint.classList.remove('ft-page-builder__field-error');
                    builderNodePropsHint.textContent = '复杂字段再改这里的 JSON；常用内容优先用上面的可视化控件。当前容器键：' + (getChildCollectionKey(node) || '无');
                } else if (field === 'style') {
                    node.style = parseJsonField(builderNodeStyle.value, 'Style');
                    builderNodeStyleHint.classList.remove('ft-page-builder__field-error');
                    builderNodeStyleHint.textContent = '复杂样式再改这里；常用边距、颜色和圆角尽量走可视化操作。';
                }
                refreshBuilder();
            } catch (error) {
                if (field === 'props') {
                    builderNodePropsHint.classList.add('ft-page-builder__field-error');
                    builderNodePropsHint.textContent = error.message;
                } else if (field === 'style') {
                    builderNodeStyleHint.classList.add('ft-page-builder__field-error');
                    builderNodeStyleHint.textContent = error.message;
                }
            }
        }

        function applyContainerConfig() {
            var node = getNodeByPath(selectedPath);
            if (!node || !isContainerNode(node)) {
                return;
            }
            setNodeNestedValue(node, 'props', 'className', builderContainerClass.value.trim());
            setNodeNestedValue(node, 'style', 'background', builderContainerBackground.value.trim());
            setNodeNestedValue(node, 'style', 'padding', builderContainerPadding.value.trim());
            setNodeNestedValue(node, 'style', 'margin', builderContainerMargin.value.trim());
            if (node.type === 'row') {
                setNodeNestedValue(node, 'style', 'gap', builderRowGap.value.trim());
            } else {
                setNodeNestedValue(node, 'style', 'gap', null);
            }
            if (node.type === 'column') {
                var span = parseInt(builderColumnSpan.value || '12', 10);
                span = Math.max(1, Math.min(span || 12, 12));
                setNodeNestedValue(node, 'props', 'span', span);
            }
            refreshBuilder();
        }

        fillButton.addEventListener('click', function () {
            if (layoutSchema.value.trim() !== '') {
                setBuilderNotice('warning', '当前布局 JSON 已有内容，确认后会覆盖成示例布局。', {
                    action: 'overwrite-default-schema',
                    confirmText: '确认覆盖',
                    cancelText: '取消'
                });
                return;
            }
            layoutSchema.value = defaultSchema;
            builderState = normalizeSchema(defaultSchema);
            selectedPath = '';
            refreshBuilder();
            setBuilderNotice('success', '已填充示例布局。你可以继续拖拽或替换成自己的内容。');
        });

        if (pageBuilderCatalog) {
            pageBuilderCatalog.addEventListener('click', function (event) {
                var sectionToggle = event.target.closest('[data-catalog-section-toggle]');
                if (sectionToggle) {
                    var groupKey = sectionToggle.getAttribute('data-catalog-section-toggle') || '';
                    catalogExpandedSections[groupKey] = !isCatalogSectionExpanded(groupKey);
                    renderCatalogList();
                    return;
                }
                var sectionMore = event.target.closest('[data-catalog-section-more]');
                if (sectionMore) {
                    var moreGroupKey = sectionMore.getAttribute('data-catalog-section-more') || '';
                    catalogExpandedSections[moreGroupKey] = !isCatalogSectionExpanded(moreGroupKey);
                    renderCatalogList();
                    return;
                }
                var button = event.target.closest('[data-builder-add]');
                if (!button) {
                    return;
                }
                insertNode(createNodeFromCatalog(button.getAttribute('data-builder-add'), button.getAttribute('data-builder-schema')));
                clearBuilderNotice();
            });
        }

        if (pageBuilderCatalogQuick) {
            pageBuilderCatalogQuick.addEventListener('click', function (event) {
                var button = event.target.closest('[data-catalog-quick-add]');
                if (!button) {
                    return;
                }
                insertNode(createNodeFromCatalog(button.getAttribute('data-catalog-quick-add'), button.getAttribute('data-catalog-quick-schema')));
                clearBuilderNotice();
            });
        }

        if (pageBuilderCatalogSearch) {
            pageBuilderCatalogSearch.addEventListener('input', function () {
                catalogKeyword = pageBuilderCatalogSearch.value || '';
                renderCatalogList();
            });
        }

        if (pageBuilderCatalogFilters) {
            pageBuilderCatalogFilters.addEventListener('click', function (event) {
                var button = event.target.closest('[data-catalog-group]');
                if (!button) {
                    return;
                }
                catalogGroup = button.getAttribute('data-catalog-group') || 'all';
                renderCatalogFilters();
                renderCatalogList();
            });
        }

        if (pageBuilderNotice) {
            pageBuilderNotice.addEventListener('click', function (event) {
                var button = event.target.closest('[data-builder-notice]');
                if (!button) {
                    return;
                }
                if (button.getAttribute('data-builder-notice') === 'confirm') {
                    executeBuilderNoticeAction();
                    return;
                }
                clearBuilderNotice();
            });
        }

        if (pageBuilderPresetList) {
            pageBuilderPresetList.addEventListener('click', function (event) {
                var button = event.target.closest('[data-builder-preset]');
                if (!button) {
                    return;
                }
                insertPresetLayout(button.getAttribute('data-builder-preset'), 'preset');
            });
        }

        if (pageBuilderTemplateList) {
            pageBuilderTemplateList.addEventListener('click', function (event) {
                var button = event.target.closest('[data-builder-template]');
                if (!button) {
                    return;
                }
                insertPresetLayout(button.getAttribute('data-builder-template'), 'template');
            });
        }

        if (builderQuickPresetList) {
            builderQuickPresetList.addEventListener('click', function (event) {
                var button = event.target.closest('[data-quick-preset]');
                if (!button) {
                    return;
                }
                applyQuickPreset(button.getAttribute('data-quick-preset'));
            });
        }

        if (builderStylePresetList) {
            builderStylePresetList.addEventListener('click', function (event) {
                var button = event.target.closest('[data-style-action]');
                if (!button) {
                    return;
                }
                applyStylePreset(button.getAttribute('data-style-action'), button.getAttribute('data-style-value') || '');
            });
        }

        if (pageFormEditor) {
            pageFormEditor.addEventListener('click', function (event) {
                var panelTipToggle = event.target.closest('[data-panel-tip-toggle]');
                if (panelTipToggle) {
                    event.preventDefault();
                    var key = panelTipToggle.getAttribute('data-panel-tip-toggle');
                    var panelTip = panelTipToggle.closest('[data-panel-tip]');
                    var isOpen = panelTip && panelTip.classList.contains('is-open');
                    closePanelTips(isOpen ? '' : key);
                    return;
                }
                if (!event.target.closest('[data-panel-tip]')) {
                    closePanelTips('');
                }
                var hintToggle = event.target.closest('[data-hint-toggle]');
                if (!hintToggle) {
                    return;
                }
                event.preventDefault();
                var hint = hintToggle.closest('.ft-page-builder__hint');
                if (!hint) {
                    return;
                }
                hint.classList.toggle('is-open');
            });
        }

        [
            [builderSectionBackgroundPicker, builderSectionBackground, '#ffffff'],
            [builderHeadingColorPicker, builderHeadingColor, '#0f172a'],
            [builderTextColorPicker, builderTextColor, '#475569'],
            [builderButtonBackgroundPicker, builderButtonBackground, '#2563eb'],
            [builderButtonColorPicker, builderButtonColor, '#ffffff']
        ].forEach(function (pair) {
            var picker = pair[0];
            var field = pair[1];
            var fallback = pair[2];
            if (!picker || !field) {
                return;
            }
            picker.addEventListener('input', function () {
                field.value = picker.value;
                applyVisualConfig();
            });
            field.addEventListener('input', function () {
                syncColorInput(field, picker, fallback);
            });
        });

        [
            [builderButtonBorderColor, '#2563eb'],
            [builderButtonHoverColor, '#ffffff'],
            [builderButtonHoverBorderColor, '#1d4ed8'],
            [builderDividerColor, '#e5e7eb'],
            [builderCtaHoverColor, '#0f172a'],
            [builderCtaHoverBorderColor, '#ffffff'],
            [builderCommonBorderColor, '#e2e8f0']
        ].forEach(function (pair) {
            enhanceColorTextInput(pair[0], pair[1]);
        });

        [
            builderPaddingTop,
            builderPaddingRight,
            builderPaddingBottom,
            builderPaddingLeft,
            builderMarginTop,
            builderMarginRight,
            builderMarginBottom,
            builderMarginLeft
        ].forEach(function (field) {
            if (!field) {
                return;
            }
            field.addEventListener('change', applyVisualConfig);
        });

        if (builderImageUploadTrigger && builderImageUploadInput) {
            builderImageUploadTrigger.addEventListener('click', function () {
                builderImageUploadInput.click();
            });
            builderImageUploadInput.addEventListener('change', function () {
                var file = builderImageUploadInput.files && builderImageUploadInput.files[0] ? builderImageUploadInput.files[0] : null;
                uploadBuilderImage(file);
                builderImageUploadInput.value = '';
            });
        }

        if (builderImageClear) {
            builderImageClear.addEventListener('click', function () {
                if (builderImageSrc) {
                    builderImageSrc.value = '';
                }
                if (builderImageAlt) {
                    builderImageAlt.value = '';
                }
                applyVisualConfig();
                renderImagePreview('');
                setFieldHelpState(builderImageUploadHint, '图片已清空。你可以重新上传，或直接粘贴图片地址。', false);
            });
        }

        if (builderImageSourceType) {
            builderImageSourceType.addEventListener('change', function () {
                syncImageSourceFields();
                applyVisualConfig();
            });
        }

        if (builderCarouselSourceType) {
            builderCarouselSourceType.addEventListener('change', function () {
                syncCarouselSourceFields();
                applyVisualConfig();
            });
        }

        if (builderVideoSourceType) {
            builderVideoSourceType.addEventListener('change', function () {
                syncVideoSourceFields();
                applyVisualConfig();
            });
        }

        if (builderGallerySourceType) {
            builderGallerySourceType.addEventListener('change', function () {
                syncGallerySourceFields();
                applyVisualConfig();
            });
        }

        if (builderCarouselSlideAdd) {
            builderCarouselSlideAdd.addEventListener('click', function () {
                updateSimpleListEditor('carousel', function (items, config) {
                    items.push(config.createItem());
                }, false);
            });
        }

        if (builderGalleryItemAdd) {
            builderGalleryItemAdd.addEventListener('click', function () {
                updateSimpleListEditor('gallery', function (items, config) {
                    items.push(config.createItem());
                }, false);
            });
        }

        if (builderFaqItemAdd) {
            builderFaqItemAdd.addEventListener('click', function () {
                updateSimpleListEditor('faq', function (items, config) {
                    items.push(config.createItem());
                }, false);
            });
        }

        if (builderStatsItemAdd) {
            builderStatsItemAdd.addEventListener('click', function () {
                updateSimpleListEditor('stats', function (items, config) {
                    items.push(config.createItem());
                }, false);
            });
        }

        if (builderNavigationItemAdd) {
            builderNavigationItemAdd.addEventListener('click', function () {
                updateNavigationItemsEditor(function (items) {
                    items.push({text: '', href: '#', children: []});
                }, false);
            });
        }

        if (builderSidebarItemAdd) {
            builderSidebarItemAdd.addEventListener('click', function () {
                updateSimpleListEditor('sidebar', function (items, config) {
                    items.push(config.createItem());
                }, false);
            });
        }

        [builderModelListPresetBar, builderModelDetailPresetBar].forEach(function (presetBar) {
            if (!presetBar) {
                return;
            }
            presetBar.addEventListener('click', function (event) {
                var button = event.target.closest('[data-model-preset]');
                if (!button) {
                    return;
                }
                event.preventDefault();
                applyModelFieldPreset(button.getAttribute('data-model-preset'));
            });
        });

        if (pageBuilderThemePresets) {
            pageBuilderThemePresets.addEventListener('click', function (event) {
                var button = event.target.closest('[data-theme-preset]');
                if (!button) {
                    return;
                }
                event.preventDefault();
                applyThemeUpdates(createThemePresetMap()[button.getAttribute('data-theme-preset')] || {});
            });
        }

        if (pageBuilderThemeReset) {
            pageBuilderThemeReset.addEventListener('click', function () {
                resetPageTheme();
            });
        }

        pageBuilderThemeFields.forEach(function (field) {
            field.addEventListener('input', function () {
                var key = field.getAttribute('data-theme-field');
                if (!key) {
                    return;
                }
                var draft = {};
                draft[key] = field.value.trim();
                previewThemeDraft(draft);
            });
            field.addEventListener('change', function () {
                var key = field.getAttribute('data-theme-field');
                if (!key) {
                    return;
                }
                var patch = {};
                patch[key] = field.value.trim();
                applyThemeUpdates(patch);
            });
        });

        pageBuilderThemeColorPickers.forEach(function (picker) {
            picker.addEventListener('input', function () {
                var key = picker.getAttribute('data-theme-color-picker');
                if (!key) {
                    return;
                }
                pageBuilderThemeFields.forEach(function (field) {
                    if (field.getAttribute('data-theme-field') === key) {
                        field.value = picker.value;
                    }
                });
                var draft = {};
                draft[key] = picker.value;
                previewThemeDraft(draft);
            });
            picker.addEventListener('change', function () {
                var key = picker.getAttribute('data-theme-color-picker');
                if (!key) {
                    return;
                }
                var patch = {};
                patch[key] = picker.value;
                applyThemeUpdates(patch);
            });
        });

        if (builderSaveReusable) {
            builderSaveReusable.addEventListener('click', function () {
                saveCurrentReusableBlock();
            });
        }

        if (builderReusableList) {
            builderReusableList.addEventListener('click', function (event) {
                var button = event.target.closest('[data-reuse-action]');
                if (!button) {
                    return;
                }
                applyReusableBlock(button.getAttribute('data-reuse-id'), button.getAttribute('data-reuse-action'));
            });
        }

        if (inspectorForm) {
            inspectorForm.addEventListener('input', function (event) {
                var simpleEditorCard = event.target.closest('[data-list-editor]');
                if (simpleEditorCard) {
                    if (event.target.matches('[data-list-color-picker]')) {
                        var textInput = simpleEditorCard.querySelector('[data-list-field="' + event.target.getAttribute('data-list-color-picker') + '"]');
                        if (textInput) {
                            textInput.value = event.target.value;
                        }
                    }
                    syncSimpleListEditor(simpleEditorCard.getAttribute('data-list-editor'), false);
                    return;
                }
                if (event.target.closest('[data-nav-root-index]')) {
                    syncNavigationItemsEditor(false);
                }
            });
            inspectorForm.addEventListener('change', function (event) {
                var simpleEditorCard = event.target.closest('[data-list-editor]');
                if (simpleEditorCard) {
                    if (event.target.matches('[data-list-refresh="1"]')) {
                        updateSimpleListEditor(simpleEditorCard.getAttribute('data-list-editor'), function () {}, false);
                    } else if (event.target.matches('[data-list-color-picker]')) {
                        syncSimpleListEditor(simpleEditorCard.getAttribute('data-list-editor'), true);
                    } else if (event.target.matches('[data-list-field]')) {
                        var colorPicker = simpleEditorCard.querySelector('[data-list-color-picker="' + event.target.getAttribute('data-list-field') + '"]');
                        if (colorPicker) {
                            colorPicker.value = normalizeColorValue(event.target.value, event.target.getAttribute('placeholder') || '#000000');
                        }
                    }
                    syncSimpleListEditor(simpleEditorCard.getAttribute('data-list-editor'), true);
                    return;
                }
                if (event.target.closest('[data-nav-root-index]')) {
                    syncNavigationItemsEditor(true);
                }
            });
            inspectorForm.addEventListener('click', function (event) {
                var moveSimpleItem = event.target.closest('[data-list-move]');
                if (moveSimpleItem) {
                    event.preventDefault();
                    updateSimpleListEditor(moveSimpleItem.getAttribute('data-list-move'), function (items) {
                        var fromIndex = parseInt(moveSimpleItem.getAttribute('data-list-index') || '-1', 10);
                        var toIndex = moveSimpleItem.getAttribute('data-list-direction') === 'up' ? fromIndex - 1 : fromIndex + 1;
                        moveArrayItem(items, fromIndex, toIndex);
                    });
                    return;
                }
                var duplicateSimpleItem = event.target.closest('[data-list-duplicate]');
                if (duplicateSimpleItem) {
                    event.preventDefault();
                    updateSimpleListEditor(duplicateSimpleItem.getAttribute('data-list-duplicate'), function (items) {
                        var index = parseInt(duplicateSimpleItem.getAttribute('data-list-index') || '-1', 10);
                        if (!items[index]) {
                            return;
                        }
                        items.splice(index + 1, 0, clonePlainItem(items[index]));
                    });
                    return;
                }
                var removeSimpleItem = event.target.closest('[data-list-remove]');
                if (removeSimpleItem) {
                    event.preventDefault();
                    updateSimpleListEditor(removeSimpleItem.getAttribute('data-list-remove'), function (items) {
                        items.splice(parseInt(removeSimpleItem.getAttribute('data-list-index') || '-1', 10), 1);
                    });
                    return;
                }
                var addNavChild = event.target.closest('[data-nav-add-child]');
                if (addNavChild) {
                    event.preventDefault();
                    updateNavigationItemsEditor(function (items) {
                        var rootIndex = parseInt(addNavChild.getAttribute('data-nav-add-child') || '-1', 10);
                        if (!items[rootIndex]) {
                            return;
                        }
                        items[rootIndex].children = Array.isArray(items[rootIndex].children) ? items[rootIndex].children : [];
                        items[rootIndex].children.push({text: '', href: '#'});
                    }, false);
                    return;
                }
                var removeNavRoot = event.target.closest('[data-nav-remove-root]');
                if (removeNavRoot) {
                    event.preventDefault();
                    updateNavigationItemsEditor(function (items) {
                        items.splice(parseInt(removeNavRoot.getAttribute('data-nav-remove-root') || '-1', 10), 1);
                    });
                    return;
                }
                var moveNavRoot = event.target.closest('[data-nav-move-root]');
                if (moveNavRoot) {
                    event.preventDefault();
                    updateNavigationItemsEditor(function (items) {
                        var fromIndex = parseInt(moveNavRoot.getAttribute('data-nav-move-root') || '-1', 10);
                        var toIndex = moveNavRoot.getAttribute('data-nav-direction') === 'up' ? fromIndex - 1 : fromIndex + 1;
                        moveArrayItem(items, fromIndex, toIndex);
                    });
                    return;
                }
                var duplicateNavRoot = event.target.closest('[data-nav-duplicate-root]');
                if (duplicateNavRoot) {
                    event.preventDefault();
                    updateNavigationItemsEditor(function (items) {
                        var index = parseInt(duplicateNavRoot.getAttribute('data-nav-duplicate-root') || '-1', 10);
                        if (!items[index]) {
                            return;
                        }
                        items.splice(index + 1, 0, clonePlainItem(items[index]));
                    });
                    return;
                }
                var removeNavChild = event.target.closest('[data-nav-remove-child]');
                if (removeNavChild) {
                    event.preventDefault();
                    updateNavigationItemsEditor(function (items) {
                        var rootIndex = parseInt(removeNavChild.getAttribute('data-nav-remove-child') || '-1', 10);
                        var childIndex = parseInt(removeNavChild.getAttribute('data-nav-child') || '-1', 10);
                        if (!items[rootIndex] || !Array.isArray(items[rootIndex].children)) {
                            return;
                        }
                        items[rootIndex].children.splice(childIndex, 1);
                    });
                    return;
                }
                var moveNavChild = event.target.closest('[data-nav-move-child]');
                if (moveNavChild) {
                    event.preventDefault();
                    updateNavigationItemsEditor(function (items) {
                        var rootIndex = parseInt(moveNavChild.getAttribute('data-nav-move-child') || '-1', 10);
                        var childIndex = parseInt(moveNavChild.getAttribute('data-nav-child') || '-1', 10);
                        var targetIndex = moveNavChild.getAttribute('data-nav-direction') === 'up' ? childIndex - 1 : childIndex + 1;
                        if (!items[rootIndex] || !Array.isArray(items[rootIndex].children)) {
                            return;
                        }
                        moveArrayItem(items[rootIndex].children, childIndex, targetIndex);
                    });
                    return;
                }
                var duplicateNavChild = event.target.closest('[data-nav-duplicate-child]');
                if (duplicateNavChild) {
                    event.preventDefault();
                    updateNavigationItemsEditor(function (items) {
                        var rootIndex = parseInt(duplicateNavChild.getAttribute('data-nav-duplicate-child') || '-1', 10);
                        var childIndex = parseInt(duplicateNavChild.getAttribute('data-nav-child') || '-1', 10);
                        if (!items[rootIndex] || !Array.isArray(items[rootIndex].children) || !items[rootIndex].children[childIndex]) {
                            return;
                        }
                        items[rootIndex].children.splice(childIndex + 1, 0, clonePlainItem(items[rootIndex].children[childIndex]));
                    });
                    return;
                }
                var outlineToggle = event.target.closest('[data-inspector-outline-toggle]');
                if (outlineToggle) {
                    inspectorOutlineExpanded = !inspectorOutlineExpanded;
                    renderInspector();
                    return;
                }
                var hintToggle = event.target.closest('[data-hint-toggle]');
                if (hintToggle) {
                    var hint = hintToggle.closest('.ft-page-builder__hint');
                    if (hint) {
                        hint.classList.toggle('is-open');
                    }
                    return;
                }
                var toggle = event.target.closest('[data-subpanel-toggle]');
                if (!toggle) {
                    return;
                }
                var panel = toggle.closest('.ft-page-builder__subpanel');
                if (!panel) {
                    return;
                }
                setSubpanelCollapsed(panel, !panel.classList.contains('is-collapsed'));
            });
        }

        if (pageBuilderInspectorTabs) {
            pageBuilderInspectorTabs.addEventListener('click', function (event) {
                var button = event.target.closest('[data-inspector-tab]');
                if (!button) {
                    return;
                }
                setInspectorTab(button.getAttribute('data-inspector-tab') || 'content');
            });
        }

        if (pageBuilderInspectorStickyActions) {
            pageBuilderInspectorStickyActions.addEventListener('click', function (event) {
                var button = event.target.closest('[data-inspector-sticky-action]');
                if (!button || !selectedPath) {
                    return;
                }
                var action = button.getAttribute('data-inspector-sticky-action');
                if (['content', 'style', 'responsive', 'advanced'].indexOf(action) !== -1) {
                    setInspectorTab(action);
                    return;
                }
                if (action === 'duplicate') {
                    duplicateNode(selectedPath);
                    return;
                }
                if (action === 'locate-preview') {
                    locateSelectedNodeInPreview();
                    return;
                }
                if (action === 'locate-editor') {
                    locateSelectedNodeInEditor();
                    return;
                }
                if (action === 'save-reuse') {
                    saveCurrentReusableBlock();
                    return;
                }
                if (action === 'delete') {
                    requestDeleteNode(selectedPath);
                }
            });
        }

        if (pageBuilderLivePreview) {
            pageBuilderLivePreview.addEventListener('click', function (event) {
                var nodeElement = event.target.closest('[data-node-id]');
                if (!nodeElement) {
                    return;
                }
                var nodePath = findNodePathById(nodeElement.getAttribute('data-node-id'), builderState, 'sections');
                if (!nodePath) {
                    return;
                }
                if (event.target.closest('[data-sidebar-panel-close]')) {
                    return;
                }
                event.preventDefault();
                selectedPath = nodePath;
                refreshBuilder();
                locateSelectedNodeEverywhere();
            });
        }

        if (pageBuilderDeviceSwitch) {
            pageBuilderDeviceSwitch.addEventListener('click', function (event) {
                var button = event.target.closest('[data-builder-device]');
                if (!button) {
                    return;
                }
                previewDevice = button.getAttribute('data-builder-device') || 'desktop';
                renderCanvasStatus();
                renderLivePreview();
                renderInspector();
            });
        }

        if (pageBuilderSelectionBar) {
            pageBuilderSelectionBar.addEventListener('click', function (event) {
                var button = event.target.closest('[data-selection-action]');
                if (!button || !selectedPath) {
                    return;
                }
                var action = button.getAttribute('data-selection-action');
                if (action === 'locate-preview') {
                    locateSelectedNodeInPreview();
                    return;
                }
                if (action === 'locate-editor') {
                    locateSelectedNodeInEditor();
                    return;
                }
                if (action === 'duplicate') {
                    duplicateNode(selectedPath);
                    return;
                }
                if (action === 'up' || action === 'down') {
                    moveNode(selectedPath, action);
                    return;
                }
                if (action === 'save-reuse') {
                    saveCurrentReusableBlock();
                    return;
                }
                if (action === 'delete') {
                    requestDeleteNode(selectedPath);
                }
            });
        }

        if (pageBuilderSelectionBreadcrumb) {
            pageBuilderSelectionBreadcrumb.addEventListener('click', function (event) {
                var button = event.target.closest('[data-breadcrumb-path]');
                if (!button) {
                    return;
                }
                selectedPath = button.getAttribute('data-breadcrumb-path') || '';
                refreshBuilder();
            });
        }

        if (pageBuilderInspectorSummary) {
            pageBuilderInspectorSummary.addEventListener('click', function (event) {
                var button = event.target.closest('[data-inspector-action]');
                if (!button || !selectedPath) {
                    return;
                }
                var action = button.getAttribute('data-inspector-action');
                if (action === 'duplicate') {
                    duplicateNode(selectedPath);
                    return;
                }
                if (action === 'save-reuse') {
                    saveCurrentReusableBlock();
                    return;
                }
                if (action === 'delete') {
                    requestDeleteNode(selectedPath);
                }
            });
        }

        if (addRootSection) {
            addRootSection.addEventListener('click', function () {
                insertNode(ensureNode({
                    type: 'section',
                    style: {
                        padding: '48px 0',
                        background: '#ffffff'
                    },
                    children: []
                }));
            });
        }

        if (reloadSchemaCanvas) {
            reloadSchemaCanvas.addEventListener('click', function () {
                try {
                    builderState = normalizeSchema(layoutSchema.value);
                    selectedPath = '';
                    refreshBuilder();
                    setBuilderNotice('success', '已从布局 JSON 重新载入画布。');
                } catch (error) {
                    setBuilderNotice('error', '布局 JSON 解析失败，请先修正后再载入。');
                }
            });
        }

        if (pageBuilderCanvas) {
            pageBuilderCanvas.addEventListener('click', function (event) {
                var menuToggle = event.target.closest('[data-builder-action="menu"]');
                if (menuToggle) {
                    event.preventDefault();
                    event.stopPropagation();
                    var actionMenu = menuToggle.closest('.ft-page-builder__node-menu');
                    [].slice.call(pageBuilderCanvas.querySelectorAll('.ft-page-builder__node-menu')).forEach(function (item) {
                        if (item !== actionMenu) {
                            item.classList.remove('is-open');
                        }
                    });
                    if (actionMenu) {
                        actionMenu.classList.toggle('is-open');
                    }
                    return;
                }

                var quickInsertButton = event.target.closest('[data-insert-menu-toggle]');
                if (quickInsertButton) {
                    event.preventDefault();
                    event.stopPropagation();
                    var zone = quickInsertButton.closest('.ft-page-builder__insert-zone');
                    if (!zone) {
                        return;
                    }
                    [].slice.call(pageBuilderCanvas.querySelectorAll('.ft-page-builder__insert-zone')).forEach(function (item) {
                        if (item !== zone) {
                            item.classList.remove('is-menu-open');
                        }
                    });
                    zone.classList.toggle('is-menu-open');
                    return;
                }

                var quickInsertItem = event.target.closest('[data-quick-insert-type]');
                if (quickInsertItem) {
                    event.preventDefault();
                    event.stopPropagation();
                    insertNodeRelative(
                        quickInsertItem.getAttribute('data-quick-insert-path'),
                        quickInsertItem.getAttribute('data-quick-insert-position'),
                        createNodeFromCatalog(
                            quickInsertItem.getAttribute('data-quick-insert-type'),
                            quickInsertItem.getAttribute('data-quick-insert-schema')
                        )
                    );
                    [].slice.call(pageBuilderCanvas.querySelectorAll('.ft-page-builder__insert-zone')).forEach(function (item) {
                        item.classList.remove('is-menu-open');
                    });
                    return;
                }

                var containerQuickButton = event.target.closest('[data-container-quick]');
                if (containerQuickButton) {
                    event.preventDefault();
                    event.stopPropagation();
                    insertNodesIntoContainer(
                        containerQuickButton.getAttribute('data-container-path'),
                        createContainerQuickNodes(containerQuickButton.getAttribute('data-container-quick'))
                    );
                    return;
                }

                var actionButton = event.target.closest('[data-builder-action]');
                if (!actionButton) {
                    [].slice.call(pageBuilderCanvas.querySelectorAll('.ft-page-builder__node-menu')).forEach(function (item) {
                        item.classList.remove('is-open');
                    });
                    var nodeCard = event.target.closest('.ft-page-builder__node');
                    if (nodeCard) {
                        selectedPath = nodeCard.getAttribute('data-path') || '';
                        refreshBuilder();
                    }
                    return;
                }
                var action = actionButton.getAttribute('data-builder-action');
                var path = actionButton.getAttribute('data-path');
                if (!path) {
                    return;
                }
                [].slice.call(pageBuilderCanvas.querySelectorAll('.ft-page-builder__node-menu')).forEach(function (item) {
                    item.classList.remove('is-open');
                });
                if (action === 'select') {
                    selectedPath = path;
                    refreshBuilder();
                    return;
                }
                if (action === 'delete') {
                    requestDeleteNode(path);
                    return;
                }
                if (action === 'duplicate') {
                    duplicateNode(path);
                    return;
                }
                if (action === 'up' || action === 'down') {
                    moveNode(path, action);
                }
            });

            pageBuilderCanvas.addEventListener('dragstart', function (event) {
                var node = event.target.closest('.ft-page-builder__node');
                if (!node) {
                    return;
                }
                dragState = {
                    path: node.getAttribute('data-path'),
                    parentCollection: node.getAttribute('data-parent-collection')
                };
                node.classList.add('is-dragging');
                event.dataTransfer.effectAllowed = 'move';
                event.dataTransfer.setData('text/plain', dragState.path);
            });

            pageBuilderCanvas.addEventListener('dragend', function () {
                dragState = null;
                [].slice.call(pageBuilderCanvas.querySelectorAll('.ft-page-builder__node')).forEach(function (node) {
                    node.classList.remove('is-dragging');
                    node.classList.remove('is-drop-target');
                });
                [].slice.call(pageBuilderCanvas.querySelectorAll('.ft-page-builder__dropzone')).forEach(function (zone) {
                    zone.classList.remove('is-drop-into');
                });
                [].slice.call(pageBuilderCanvas.querySelectorAll('.ft-page-builder__insert-zone')).forEach(function (zone) {
                    zone.classList.remove('is-active');
                });
            });

            pageBuilderCanvas.addEventListener('dragover', function (event) {
                var siblingZone = event.target.closest('[data-builder-drop-zone="before"], [data-builder-drop-zone="after"]');
                if (siblingZone && dragState) {
                    var siblingTargetPath = siblingZone.getAttribute('data-path');
                    if (siblingTargetPath && getParentCollectionPath(siblingTargetPath) === dragState.parentCollection && siblingTargetPath !== dragState.path) {
                        event.preventDefault();
                        siblingZone.classList.add('is-active');
                        return;
                    }
                }
                var targetZone = event.target.closest('[data-builder-drop-zone="into"]');
                if (targetZone && dragState) {
                    var zonePath = targetZone.getAttribute('data-path');
                    if (zonePath && !isSameOrChildPath(dragState.path, zonePath)) {
                        event.preventDefault();
                        targetZone.classList.add('is-drop-into');
                        return;
                    }
                }
                var targetNode = event.target.closest('.ft-page-builder__node');
                if (!targetNode || !dragState) {
                    return;
                }
                if (targetNode.getAttribute('data-parent-collection') !== dragState.parentCollection) {
                    return;
                }
                event.preventDefault();
                targetNode.classList.add('is-drop-target');
            });

            pageBuilderCanvas.addEventListener('dragleave', function (event) {
                var siblingZone = event.target.closest('[data-builder-drop-zone="before"], [data-builder-drop-zone="after"]');
                if (siblingZone) {
                    siblingZone.classList.remove('is-active');
                }
                var targetZone = event.target.closest('[data-builder-drop-zone="into"]');
                if (targetZone) {
                    targetZone.classList.remove('is-drop-into');
                }
                var targetNode = event.target.closest('.ft-page-builder__node');
                if (targetNode) {
                    targetNode.classList.remove('is-drop-target');
                }
            });

            pageBuilderCanvas.addEventListener('drop', function (event) {
                var siblingZone = event.target.closest('[data-builder-drop-zone="before"], [data-builder-drop-zone="after"]');
                if (siblingZone && dragState) {
                    siblingZone.classList.remove('is-active');
                    event.preventDefault();
                    moveNodeRelative(dragState.path, siblingZone.getAttribute('data-path'), siblingZone.getAttribute('data-builder-drop-zone'));
                    return;
                }
                var targetZone = event.target.closest('[data-builder-drop-zone="into"]');
                if (targetZone && dragState) {
                    targetZone.classList.remove('is-drop-into');
                    event.preventDefault();
                    moveNodeIntoContainer(dragState.path, targetZone.getAttribute('data-path'));
                    return;
                }
                var targetNode = event.target.closest('.ft-page-builder__node');
                if (!targetNode || !dragState) {
                    return;
                }
                targetNode.classList.remove('is-drop-target');
                if (targetNode.getAttribute('data-parent-collection') !== dragState.parentCollection) {
                    return;
                }
                event.preventDefault();
                var collection = getCollectionByPath(dragState.parentCollection);
                if (!collection) {
                    return;
                }
                var fromIndex = getPathIndex(dragState.path);
                var toIndex = getPathIndex(targetNode.getAttribute('data-path'));
                if (fromIndex === toIndex) {
                    return;
                }
                var moved = collection.splice(fromIndex, 1)[0];
                collection.splice(toIndex, 0, moved);
                selectedPath = dragState.parentCollection + '.' + toIndex;
                refreshBuilder();
            });
        }

        document.addEventListener('click', function (event) {
            if (!pageBuilderCanvas || event.target.closest('#pageBuilderCanvas')) {
                return;
            }
            [].slice.call(pageBuilderCanvas.querySelectorAll('.ft-page-builder__insert-zone')).forEach(function (item) {
                item.classList.remove('is-menu-open');
            });
        });

        initializeCollapsibleSubpanels();
        initializeCollapsibleHints();
        scheduleInspectorFloatingPositionSync();

        document.addEventListener('scroll', scheduleInspectorFloatingPositionSync, true);
        window.addEventListener('scroll', scheduleInspectorFloatingPositionSync, {passive: true});
        window.addEventListener('resize', scheduleInspectorFloatingPositionSync);
        window.addEventListener('load', scheduleInspectorFloatingPositionSync);

        if (pageFormEditor) {
            pageFormEditor.addEventListener('input', function (event) {
                if (!event.target || !event.target.name) {
                    return;
                }
                refreshPageFormState();
            });
            pageFormEditor.addEventListener('change', function (event) {
                if (!event.target || !event.target.name) {
                    return;
                }
                refreshPageFormState();
            });
            pageFormEditor.addEventListener('submit', function () {
                updateFloatingMeta();
                setPageFormDirty(false, '保存中...');
                if (pageFormFloatingSubmit) {
                    pageFormFloatingSubmit.textContent = '保存中...';
                }
            });
        }

        window.addEventListener('beforeunload', function (event) {
            if (!pageFormDirty) {
                return;
            }
            event.preventDefault();
            event.returnValue = '';
        });

        if (builderNodeId) {
            builderNodeId.addEventListener('change', function () {
                applyInspectorField('id');
            });
        }

        if (builderNodeProps) {
            builderNodeProps.addEventListener('change', function () {
                applyInspectorField('props');
            });
        }

        if (builderNodeStyle) {
            builderNodeStyle.addEventListener('change', function () {
                applyInspectorField('style');
            });
        }

        [
            builderSectionContentWidth,
            builderSectionInnerWidth,
            builderHeadingText,
            builderHeadingLevel,
            builderHeadingAlign,
            builderHeadingColor,
            builderHeadingFontSize,
            builderTextContent,
            builderTextAlign,
            builderTextColor,
            builderTextFontSize,
            builderTextLineHeight,
            builderButtonText,
            builderButtonHref,
            builderButtonTarget,
            builderButtonAlign,
            builderButtonVariant,
            builderButtonBackground,
            builderButtonColor,
            builderButtonRadius,
            builderButtonMinHeight,
            builderButtonBorderColor,
            builderButtonPadding,
            builderButtonHoverBackground,
            builderButtonHoverColor,
            builderButtonHoverBorderColor,
            builderButtonHoverShadow,
            builderImageSourceType,
            builderImageModel,
            builderImageRecordId,
            builderImageField,
            builderImageSrc,
            builderImageAlt,
            builderImageWidth,
            builderImageRadius,
            builderImageObjectFit,
            builderImageAlign,
            builderImageMinHeight,
            builderImageHoverScale,
            builderImageHoverShadow,
            builderDividerColor,
            builderDividerThickness,
            builderDividerStyle,
            builderDividerWidth,
            builderHtmlContent,
            builderCarouselSourceType,
            builderCarouselAutoplay,
            builderCarouselInterval,
            builderCarouselButtonText,
            builderCarouselButtonHref,
            builderCarouselModel,
            builderCarouselLimit,
            builderCarouselTitleField,
            builderCarouselSummaryField,
            builderCarouselImageField,
            builderCarouselUrlField,
            builderCarouselDetailPrefix,
            builderCarouselSlides,
            builderVideoSourceType,
            builderVideoTitle,
            builderVideoEmbedUrl,
            builderVideoMp4Url,
            builderVideoPoster,
            builderVideoAspectRatio,
            builderVideoControls,
            builderVideoAutoplay,
            builderVideoMuted,
            builderVideoLoop,
            builderModelListTitle,
            builderModelListTemplate,
            builderModelListModel,
            builderModelListLimit,
            builderModelListOrderBy,
            builderModelListOrderDirection,
            builderModelListTitleField,
            builderModelListSummaryField,
            builderModelListImageField,
            builderModelListDateField,
            builderModelListUrlField,
            builderModelListDetailPrefix,
            builderModelDetailTitle,
            builderModelDetailTemplate,
            builderModelDetailModel,
            builderModelDetailRecordId,
            builderModelDetailTitleField,
            builderModelDetailSummaryField,
            builderModelDetailContentField,
            builderModelDetailImageField,
            builderModelDetailDateField,
            builderModelDetailUrlField,
            builderModelDetailDetailPrefix,
            builderGalleryTitle,
            builderGallerySubtitle,
            builderGallerySourceType,
            builderGalleryColumns,
            builderGalleryGap,
            builderGalleryModel,
            builderGalleryLimit,
            builderGalleryTitleField,
            builderGalleryImageField,
            builderGalleryUrlField,
            builderGalleryDetailPrefix,
            builderGalleryItems,
            builderFaqTitle,
            builderFaqIntro,
            builderFaqColumns,
            builderFaqItems,
            builderStatsTitle,
            builderStatsIntro,
            builderStatsColumns,
            builderStatsItems,
            builderCtaEyebrow,
            builderCtaTitle,
            builderCtaDescription,
            builderCtaPrimaryText,
            builderCtaPrimaryHref,
            builderCtaSecondaryText,
            builderCtaSecondaryHref,
            builderCtaAlign,
            builderCtaActionsAlign,
            builderCtaPrimaryVariant,
            builderCtaSecondaryVariant,
            builderCtaButtonMinHeight,
            builderCtaHoverBackground,
            builderCtaHoverColor,
            builderCtaHoverBorderColor,
            builderCtaHoverShadow,
            builderNavigationTitle,
            builderNavigationLogoType,
            builderNavigationLayout,
            builderNavigationBrandHref,
            builderNavigationCtaText,
            builderNavigationCtaHref,
            builderNavigationLogoImage,
            builderNavigationLogoSvg,
            builderNavigationItems,
            builderSidebarTitle,
            builderSidebarPosition,
            builderSidebarOffsetTop,
            builderSidebarShowBackTop,
            builderSidebarItems,
            builderQrCodeTitle,
            builderQrCodeText,
            builderQrCodeValue,
            builderQrCodeSize,
            builderLoginTitle,
            builderLoginAction,
            builderLoginButtonText,
            builderLoginProfileText,
            builderLoginProfileHref,
            builderLoginAvatarUrl,
            builderAnchorId,
            builderCommonWidth,
            builderCommonMinHeight,
            builderCommonRadius,
            builderCommonBorderWidth,
            builderCommonBorderStyle,
            builderCommonBorderColor,
            builderCommonBoxShadow,
            builderAnimationEffect,
            builderAnimationDuration,
            builderAnimationDelay
        ].forEach(function (field) {
            if (!field) {
                return;
            }
            field.addEventListener('change', applyVisualConfig);
        });

        [
            builderVisibilityEffect,
            builderVisibilityRule,
            builderVisibilityLogic,
            builderVisibilityExtraRule,
            builderVisibilityParam,
            builderVisibilityValue,
            builderVisibilityDevices,
            builderVisibilityExtraParam,
            builderVisibilityExtraValue,
            builderVisibilityExtraDevices
        ].forEach(function (field) {
            if (!field) {
                return;
            }
            field.addEventListener('change', function () {
                syncVisibilityRuleFields();
                applyVisualConfig();
            });
        });

        if (builderTabletStyle) {
            builderTabletStyle.addEventListener('change', function () {
                applyResponsiveStyle('tablet');
            });
        }

        if (builderMobileStyle) {
            builderMobileStyle.addEventListener('change', function () {
                applyResponsiveStyle('mobile');
            });
        }

        [
            builderTabletPadding,
            builderTabletMargin,
            builderTabletFontSize,
            builderTabletGap
        ].forEach(function (field) {
            if (!field) {
                return;
            }
            field.addEventListener('change', function () {
                applyResponsiveVisualConfig('tablet');
            });
        });

        [
            builderMobilePadding,
            builderMobileMargin,
            builderMobileFontSize,
            builderMobileGap
        ].forEach(function (field) {
            if (!field) {
                return;
            }
            field.addEventListener('change', function () {
                applyResponsiveVisualConfig('mobile');
            });
        });

        if (builderTabletSpan) {
            builderTabletSpan.addEventListener('change', function () {
                applyResponsiveSpan('tablet');
            });
        }

        if (builderMobileSpan) {
            builderMobileSpan.addEventListener('change', function () {
                applyResponsiveSpan('mobile');
            });
        }

        [
            builderContainerClass,
            builderContainerBackground,
            builderContainerPadding,
            builderContainerMargin,
            builderRowGap,
            builderColumnSpan
        ].forEach(function (field) {
            if (!field) {
                return;
            }
            field.addEventListener('change', applyContainerConfig);
        });

        lastSavedSnapshot = serializeFormState();
        refreshBuilder();
    })();
</script>
</body>
