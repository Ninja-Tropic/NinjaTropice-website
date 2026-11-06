( function () {
	'use strict';

	function init( root ) {
		var ajaxUrl = root.getAttribute( 'data-ajax' );
		var config  = root.getAttribute( 'data-config' );
		var sig     = root.getAttribute( 'data-sig' );
		if ( ! ajaxUrl || ! config || ! sig ) return;

		var grid         = root.querySelector( '.pf__grid' );
		var chipsWrap    = root.querySelector( '.pf__active' );
		var emptyMsg     = root.querySelector( '.pf__empty' );
		var countEl      = root.querySelector( '.pf__count' );
		var loadMoreWrap = root.querySelector( '.pf__load-more-wrap' );
		var loadMoreBtn  = root.querySelector( '.pf__load-more' );
		var searchInput  = root.querySelector( '.pf__search-input' );
		var dropdowns    = Array.from( root.querySelectorAll( '.pf__sd' ) );

		// Selected values per axis, keyed by data-axis.
		var state = {};
		dropdowns.forEach( function ( sd ) { state[ sd.dataset.axis ] = new Set(); } );

		var currentPage = 1;
		var shown       = grid.children.length;
		var total       = parseInt( root.getAttribute( 'data-total' ), 10 ) || 0;
		var searchQuery = '';
		var searchTimer = null;
		var requestId   = 0; // only the latest request may update the grid

		// ── AJAX ──────────────────────────────────────────────────────────────────

		function fetchPage( page, replace ) {
			var myRequest = ++requestId;
			setLoading( true );

			var params = new URLSearchParams();
			params.set( 'action', 'ninjatheme_content_grid' );
			params.set( 'config', config );
			params.set( 'sig', sig );
			params.set( 'page', String( page ) );
			if ( searchQuery ) params.set( 'search', searchQuery );

			Object.keys( state ).forEach( function ( axis ) {
				state[ axis ].forEach( function ( val ) {
					params.append( 'filters[' + axis + '][]', val );
				} );
			} );

			fetch( ajaxUrl, {
				method  : 'POST',
				headers : { 'Content-Type': 'application/x-www-form-urlencoded' },
				body    : params.toString(),
			} )
				.then( function ( r ) { return r.json(); } )
				.then( function ( data ) {
					if ( myRequest !== requestId ) return;
					if ( ! data.success ) { setLoading( false ); return; }

					var d   = data.data;
					var tmp = document.createElement( 'div' );
					tmp.innerHTML = d.html;
					var batch = tmp.children.length;

					if ( replace ) {
						replaceGrid( d.html );
						shown = batch;
					} else {
						appendToGrid( tmp );
						shown += batch;
					}

					currentPage = page;
					total       = d.total;
					updateMeta( d.has_more );
					setLoading( false );
				} )
				.catch( function () {
					if ( myRequest === requestId ) setLoading( false );
				} );
		}

		function applyFilters() {
			currentPage = 1;
			fetchPage( 1, true );
		}

		// ── Grid ──────────────────────────────────────────────────────────────────

		function replaceGrid( html ) {
			Array.from( grid.children ).forEach( function ( card ) {
				card.style.transition = 'opacity 0.18s ease, transform 0.18s ease';
				card.style.opacity    = '0';
				card.style.transform  = 'scale(0.97)';
			} );

			var expected = requestId;
			setTimeout( function () {
				if ( expected !== requestId ) return; // a newer request owns the grid
				grid.innerHTML = html;
				entrance( Array.from( grid.children ) );
			}, 200 );
		}

		function appendToGrid( tmp ) {
			var cards = Array.from( tmp.children );
			cards.forEach( function ( card ) { grid.appendChild( card ); } );
			entrance( cards );
		}

		function entrance( cards ) {
			cards.forEach( function ( card, i ) {
				card.style.opacity    = '0';
				card.style.transform  = 'translateY(16px) scale(0.97)';
				card.style.transition = 'none';

				setTimeout( function () {
					card.style.transition = 'opacity 0.38s ease, transform 0.38s ease';
					card.style.opacity    = '';
					card.style.transform  = '';
				}, i * 50 );
			} );
		}

		// ── Meta ──────────────────────────────────────────────────────────────────

		function updateMeta( hasMore ) {
			if ( loadMoreWrap ) loadMoreWrap.hidden = ! hasMore;
			if ( emptyMsg ) emptyMsg.hidden = total > 0;
			if ( countEl ) {
				countEl.textContent = total === 0 ? '' : 'Showing ' + Math.min( shown, total ) + ' of ' + total;
			}
		}

		function setLoading( loading ) {
			if ( ! loadMoreBtn ) return;
			loadMoreBtn.disabled = loading;
			loadMoreBtn.classList.toggle( 'is-loading', loading );
		}

		// ── Chips ─────────────────────────────────────────────────────────────────

		function renderChips() {
			chipsWrap.innerHTML = '';
			var hasActive = false;

			Object.keys( state ).forEach( function ( axis ) {
				state[ axis ].forEach( function ( val ) {
					hasActive = true;

					var chip  = document.createElement( 'span' );
					chip.className = 'pf__chip';

					var label = document.createElement( 'span' );
					label.textContent = val;

					var btn = document.createElement( 'button' );
					btn.type = 'button';
					btn.setAttribute( 'aria-label', 'Remove filter: ' + val );
					btn.innerHTML = '<svg width="10" height="10" viewBox="0 0 10 10" fill="none" aria-hidden="true"><path d="M1.5 1.5l7 7M8.5 1.5l-7 7" stroke="currentColor" stroke-width="1.75" stroke-linecap="round"/></svg>';
					btn.addEventListener( 'click', function () {
						state[ axis ].delete( val );
						syncAll();
						applyFilters();
					} );

					chip.appendChild( label );
					chip.appendChild( btn );
					chipsWrap.appendChild( chip );
				} );
			} );

			if ( hasActive ) {
				var title = document.createElement( 'span' );
				title.className   = 'pf__active-label';
				title.textContent = 'Active Filters';
				chipsWrap.insertBefore( title, chipsWrap.firstChild );

				var clear = document.createElement( 'button' );
				clear.type        = 'button';
				clear.className   = 'pf__clear-all';
				clear.textContent = 'Clear All';
				clear.addEventListener( 'click', clearAll );
				chipsWrap.appendChild( clear );
			}

			chipsWrap.hidden = ! hasActive;
		}

		function clearAll() {
			Object.keys( state ).forEach( function ( axis ) { state[ axis ].clear(); } );
			searchQuery = '';
			if ( searchInput ) searchInput.value = '';
			syncAll();
			applyFilters();
		}

		// ── Dropdowns ─────────────────────────────────────────────────────────────

		function syncDropdown( sd ) {
			var axis   = sd.dataset.axis;
			var values = state[ axis ];
			var n      = values.size;
			var label  = sd.querySelector( '.pf__sd-label' );
			var count  = sd.querySelector( '.pf__sd-count' );

			// Show the first selection and "+N" for the rest.
			label.textContent = n > 0 ? Array.from( values )[ 0 ] : label.dataset.placeholder;
			count.hidden      = n < 2;
			count.textContent = n > 1 ? '+' + ( n - 1 ) : '';
			sd.classList.toggle( 'has-active', n > 0 );

			sd.querySelectorAll( '.pf__sd-item' ).forEach( function ( item ) {
				var selected = values.has( item.dataset.value );
				item.classList.toggle( 'is-selected', selected );
				item.setAttribute( 'aria-selected', selected ? 'true' : 'false' );
			} );
		}

		function syncAll() {
			dropdowns.forEach( syncDropdown );
			renderChips();
		}

		function closeAll() {
			dropdowns.forEach( function ( sd ) {
				sd.querySelector( '.pf__sd-panel' ).hidden = true;
				sd.classList.remove( 'is-open' );
			} );
		}

		dropdowns.forEach( function ( sd ) {
			var trigger = sd.querySelector( '.pf__sd-trigger' );
			var panel   = sd.querySelector( '.pf__sd-panel' );

			trigger.addEventListener( 'click', function ( e ) {
				e.stopPropagation();
				var wasOpen = ! panel.hidden;
				closeAll();
				panel.hidden = wasOpen;
				sd.classList.toggle( 'is-open', ! wasOpen );
			} );

			panel.addEventListener( 'click', function ( e ) {
				e.stopPropagation();
				var item = e.target.closest( '.pf__sd-item' );
				if ( ! item ) return;

				var axis = sd.dataset.axis;
				var val  = item.dataset.value;
				if ( state[ axis ].has( val ) ) {
					state[ axis ].delete( val );
				} else {
					state[ axis ].add( val );
				}

				syncAll();
				applyFilters();
			} );
		} );

		document.addEventListener( 'click', closeAll );
		document.addEventListener( 'keydown', function ( e ) {
			if ( e.key === 'Escape' ) closeAll();
		} );

		// ── Search ────────────────────────────────────────────────────────────────

		if ( searchInput ) {
			searchInput.addEventListener( 'input', function () {
				clearTimeout( searchTimer );
				searchTimer = setTimeout( function () {
					searchQuery = searchInput.value.trim();
					applyFilters();
				}, 350 );
			} );

			searchInput.addEventListener( 'keydown', function ( e ) {
				if ( e.key === 'Escape' ) {
					searchInput.value = '';
					searchQuery = '';
					applyFilters();
				}
			} );
		}

		// ── Load more ─────────────────────────────────────────────────────────────

		if ( loadMoreBtn ) {
			loadMoreBtn.addEventListener( 'click', function () {
				fetchPage( currentPage + 1, false );
			} );
		}

		updateMeta( root.getAttribute( 'data-has-more' ) === '1' );
	}

	document.querySelectorAll( '.pf--cg' ).forEach( init );
} )();
