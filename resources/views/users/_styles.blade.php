    .user-metrics {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 14px;
        margin-bottom: 22px;
    }

    .user-metric {
        padding: 20px;
        border: 1px solid var(--line-soft);
        border-top: 3px solid var(--blue-500);
        background: var(--surface);
        box-shadow: var(--shadow);
    }

    .user-metric.admins { border-top-color: var(--success); }
    .user-metric.standard { border-top-color: var(--muted); }
    .user-metric-label { margin: 0; color: var(--muted); font-size: .68rem; font-weight: 800; letter-spacing: .09em; text-transform: uppercase; }
    .user-metric-value { display: block; margin-top: 9px; color: var(--ink); font-size: 1.9rem; line-height: 1; }
    .users-panel { overflow: hidden; }
    .users-panel-header { display: flex; align-items: flex-start; justify-content: space-between; gap: 20px; padding: 22px 24px; border-bottom: 1px solid var(--line-soft); }
    .panel-kicker { margin: 0 0 7px; color: var(--accent-text); font-size: .63rem; font-weight: 850; letter-spacing: .14em; text-transform: uppercase; }
    .panel-title { margin: 0; color: var(--ink); font-size: 1.1rem; }
    .panel-copy { margin: 7px 0 0; color: var(--muted); font-size: .76rem; line-height: 1.5; }
    .user-filters { display: grid; grid-template-columns: minmax(240px, 1fr) 190px auto; gap: 10px; padding: 18px 24px; border-bottom: 1px solid var(--line-soft); background: var(--surface-raised); }
    .filter-actions { display: flex; gap: 8px; }
    .users-table-wrap { overflow-x: auto; }
    .users-table { width: 100%; border-collapse: collapse; }
    .users-table th { padding: 12px 18px; color: var(--muted); background: var(--surface-raised); font-size: .63rem; font-weight: 850; letter-spacing: .07em; text-align: left; text-transform: uppercase; white-space: nowrap; }
    .users-table td { padding: 16px 18px; border-top: 1px solid var(--line-soft); font-size: .76rem; vertical-align: middle; }
    .users-table tbody tr:hover { background: var(--surface-hover); }
    .user-identity { display: flex; align-items: center; gap: 12px; min-width: 220px; }
    .user-avatar { width: 36px; height: 36px; display: grid; place-items: center; flex: 0 0 auto; color: #fff; background: var(--navy-800); font-size: .68rem; font-weight: 850; letter-spacing: .04em; }
    .cell-primary { display: block; color: var(--ink); font-weight: 750; }
    .cell-secondary { display: block; margin-top: 4px; color: var(--muted); font-size: .68rem; }
    .row-actions { display: flex; gap: 7px; white-space: nowrap; }
    .empty-users { padding: 58px 24px; text-align: center; }
    .empty-users h3 { margin: 0; color: var(--ink); }
    .empty-users p { margin: 9px 0 18px; color: var(--muted); font-size: .8rem; }
    .users-pagination { display: flex; align-items: center; justify-content: space-between; gap: 16px; padding: 16px 24px; border-top: 1px solid var(--line-soft); color: var(--muted); font-size: .72rem; }

    .user-form-shell { display: grid; grid-template-columns: minmax(0, 1fr) 300px; gap: 20px; align-items: start; }
    .user-form-panel { padding: 26px; }
    .form-section + .form-section { margin-top: 28px; padding-top: 25px; border-top: 1px solid var(--line-soft); }
    .form-section-title { margin: 0 0 5px; color: var(--ink); font-size: .95rem; }
    .form-section-copy { margin: 0 0 19px; color: var(--muted); font-size: .74rem; line-height: 1.55; }
    .form-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 18px; }
    .field-wide { grid-column: 1 / -1; }
    .form-actions { display: flex; gap: 10px; margin-top: 26px; padding-top: 22px; border-top: 1px solid var(--line-soft); }
    .form-help { margin: 7px 0 0; color: var(--muted); font-size: .66rem; line-height: 1.45; }
    .user-side-panel { padding: 22px; }
    .user-side-panel h2 { margin: 0; color: var(--ink); font-size: .9rem; }
    .user-side-panel p, .user-side-panel li { color: var(--muted); font-size: .72rem; line-height: 1.6; }
    .user-side-panel ul { margin: 14px 0 0; padding-left: 18px; }
    .detail-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); }
    .detail-item { min-height: 112px; padding: 22px; border-bottom: 1px solid var(--line-soft); }
    .detail-item:nth-child(odd) { border-right: 1px solid var(--line-soft); }
    .detail-label { display: block; margin-bottom: 10px; color: var(--muted); font-size: .64rem; font-weight: 800; letter-spacing: .08em; text-transform: uppercase; }
    .detail-value { color: var(--ink); font-size: .88rem; font-weight: 720; word-break: break-word; }
    .danger-zone { margin-top: 20px; padding: 20px 22px; border: 1px solid #efc9c5; background: var(--danger-soft); }
    .danger-zone h2 { margin: 0; color: var(--danger); font-size: .9rem; }
    .danger-zone p { margin: 7px 0 15px; color: #8f342d; font-size: .72rem; line-height: 1.55; }

    @media (max-width: 850px) {
        .user-form-shell { grid-template-columns: 1fr; }
        .user-filters { grid-template-columns: 1fr 1fr; }
        .user-filters .filter-actions { grid-column: 1 / -1; }
    }

    @media (max-width: 620px) {
        .user-metrics, .form-grid, .detail-grid { grid-template-columns: 1fr; }
        .detail-item:nth-child(odd) { border-right: 0; }
        .user-filters { grid-template-columns: 1fr; padding: 16px; }
        .user-filters .filter-actions { grid-column: auto; }
        .users-panel-header { padding: 19px 16px; }
        .users-pagination { align-items: stretch; flex-direction: column; padding: 16px; }
    }
