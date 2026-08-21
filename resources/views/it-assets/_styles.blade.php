    .asset-summary-grid {
        display: grid;
        grid-template-columns: repeat(6, minmax(0, 1fr));
        gap: 12px;
        margin-bottom: 18px;
    }

    .asset-metric {
        min-width: 0;
        padding: 18px;
        border-top: 3px solid var(--blue-500);
    }

    .asset-metric.attention {
        border-top-color: var(--danger);
    }

    .asset-metric.stock {
        border-top-color: var(--warning);
    }

    .asset-metric-label {
        display: block;
        color: var(--muted);
        font-size: 0.62rem;
        font-weight: 820;
        letter-spacing: 0.1em;
        text-transform: uppercase;
    }

    .asset-metric-value {
        display: block;
        margin-top: 9px;
        color: var(--ink);
        font-size: clamp(1.5rem, 2.5vw, 2rem);
        font-weight: 780;
        line-height: 1;
    }

    .asset-metric-note {
        display: block;
        margin-top: 8px;
        color: var(--muted);
        font-size: 0.67rem;
        line-height: 1.4;
    }

    .asset-toolbar {
        margin-bottom: 18px;
        padding: 18px;
    }

    .asset-filters {
        display: grid;
        grid-template-columns: minmax(220px, 2fr) repeat(4, minmax(140px, 1fr)) auto;
        align-items: end;
        gap: 12px;
    }

    .asset-filter-actions {
        display: flex;
        gap: 8px;
    }

    .asset-import {
        margin-top: 14px;
        border-top: 1px solid var(--line-soft);
        padding-top: 14px;
    }

    .asset-import summary {
        width: fit-content;
        color: var(--accent-text);
        cursor: pointer;
        font-size: 0.72rem;
        font-weight: 780;
    }

    .asset-import-form {
        display: grid;
        grid-template-columns: minmax(240px, 1fr) auto;
        align-items: end;
        gap: 12px;
        max-width: 680px;
        margin-top: 13px;
    }

    .asset-table-panel {
        overflow: hidden;
    }

    .asset-table-heading {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        padding: 18px 20px;
        border-bottom: 1px solid var(--line-soft);
    }

    .asset-table-heading h2 {
        margin: 0;
        color: var(--ink);
        font-size: 0.94rem;
        font-weight: 780;
    }

    .asset-table-heading p {
        margin: 5px 0 0;
        color: var(--muted);
        font-size: 0.68rem;
    }

    .asset-table-wrap {
        overflow-x: auto;
    }

    .asset-table {
        width: 100%;
        min-width: 1160px;
        border-collapse: collapse;
    }

    .asset-table th {
        padding: 12px 14px;
        border-bottom: 1px solid var(--line);
        color: var(--muted);
        background: var(--surface-raised);
        font-size: 0.61rem;
        font-weight: 820;
        letter-spacing: 0.075em;
        text-align: left;
        text-transform: uppercase;
        white-space: nowrap;
    }

    .asset-table td {
        padding: 14px;
        border-bottom: 1px solid var(--line-soft);
        color: var(--muted);
        font-size: 0.73rem;
        line-height: 1.45;
        vertical-align: top;
    }

    .asset-table tbody tr:hover {
        background: var(--surface-hover);
    }

    .asset-primary {
        display: block;
        color: var(--ink);
        font-weight: 760;
    }

    .asset-secondary {
        display: block;
        margin-top: 4px;
        color: var(--muted);
        font-size: 0.66rem;
    }

    .asset-code {
        font-family: "Cascadia Code", Consolas, monospace;
        font-size: 0.66rem;
        overflow-wrap: anywhere;
    }

    .asset-actions {
        display: flex;
        gap: 7px;
        white-space: nowrap;
    }

    .asset-pagination {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        padding: 15px 20px;
        color: var(--muted);
        font-size: 0.68rem;
    }

    .asset-pagination-actions {
        display: flex;
        align-items: center;
        gap: 7px;
    }

    .button.disabled {
        pointer-events: none;
        opacity: 0.48;
    }

    .asset-empty {
        padding: 62px 24px;
        text-align: center;
    }

    .asset-empty h3 {
        margin: 0;
        color: var(--ink);
        font-size: 1.05rem;
    }

    .asset-empty p {
        max-width: 520px;
        margin: 10px auto 20px;
        color: var(--muted);
        font-size: 0.77rem;
        line-height: 1.6;
    }

    .asset-form-panel {
        overflow: hidden;
    }

    .asset-form-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 18px;
        padding: 24px;
    }

    .asset-section-heading {
        grid-column: 1 / -1;
        margin: 8px 0 0;
        padding: 0 0 10px;
        border-bottom: 1px solid var(--line-soft);
        color: var(--ink);
        font-size: 0.82rem;
        font-weight: 800;
        letter-spacing: 0.02em;
    }

    .asset-section-heading:first-child {
        margin-top: 0;
    }

    .asset-field.span-2 {
        grid-column: 1 / -1;
    }

    .asset-field-help {
        margin: 6px 0 0;
        color: var(--muted);
        font-size: 0.64rem;
        line-height: 1.45;
    }

    .required {
        color: var(--danger);
    }

    .asset-form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        padding: 18px 24px;
        border-top: 1px solid var(--line-soft);
        background: var(--surface-raised);
    }

    .asset-hero {
        display: grid;
        grid-template-columns: minmax(0, 1fr) auto;
        align-items: start;
        gap: 20px;
        margin-bottom: 18px;
        padding: 22px;
    }

    .asset-hero h2 {
        margin: 0;
        color: var(--ink);
        font-size: 1.25rem;
    }

    .asset-hero p {
        margin: 8px 0 0;
        color: var(--muted);
        font-size: 0.75rem;
    }

    .asset-hero-badges {
        display: flex;
        flex-wrap: wrap;
        justify-content: flex-end;
        gap: 8px;
    }

    .asset-details {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 18px;
    }

    .asset-detail-card {
        padding: 22px;
    }

    .asset-detail-card.wide {
        grid-column: 1 / -1;
    }

    .asset-detail-card h3 {
        margin: 0 0 17px;
        color: var(--ink);
        font-size: 0.83rem;
    }

    .asset-detail-list {
        display: grid;
        grid-template-columns: minmax(120px, 0.65fr) minmax(0, 1fr);
        gap: 0;
        margin: 0;
    }

    .asset-detail-list dt,
    .asset-detail-list dd {
        margin: 0;
        padding: 10px 0;
        border-bottom: 1px solid var(--line-soft);
        font-size: 0.72rem;
        line-height: 1.5;
    }

    .asset-detail-list dt {
        color: var(--muted);
        font-weight: 700;
    }

    .asset-detail-list dd {
        color: var(--ink);
        overflow-wrap: anywhere;
    }

    .asset-remarks {
        margin: 0;
        color: var(--muted);
        font-size: 0.76rem;
        line-height: 1.7;
        white-space: pre-wrap;
    }

    @media (max-width: 1380px) {
        .asset-summary-grid {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }

        .asset-filters {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }
    }

    @media (max-width: 820px) {
        .asset-summary-grid,
        .asset-details {
            grid-template-columns: 1fr 1fr;
        }

        .asset-filters,
        .asset-form-grid,
        .asset-import-form {
            grid-template-columns: 1fr;
        }

        .asset-field.span-2,
        .asset-detail-card.wide {
            grid-column: auto;
        }

        .asset-filter-actions,
        .asset-filter-actions .button {
            width: 100%;
        }

        .asset-hero {
            grid-template-columns: 1fr;
        }

        .asset-hero-badges {
            justify-content: flex-start;
        }
    }

    @media (max-width: 560px) {
        .asset-summary-grid,
        .asset-details {
            grid-template-columns: 1fr;
        }

        .asset-table-heading,
        .asset-pagination {
            align-items: stretch;
            flex-direction: column;
        }

        .asset-pagination-actions {
            display: grid;
            grid-template-columns: 1fr 1fr;
        }
    }
