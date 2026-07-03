( function () {
	'use strict';

	var wrap = document.getElementById( 'blog-filter-wrap' );
	if ( ! wrap || typeof bfData === 'undefined' ) return;

	var grid         = document.getElementById( 'bf-grid' );
	var chipsWrap    = document.getElementById( 'bf-active-chips' );
	var emptyMsg     = document.getElementById( 'bf-empty' );
	var countEl      = document.getElementById( 'bf-count' );
	var loadMoreWrap = document.getElementById( 'bf-load-more-wrap' );
	var loadMoreBtn  = document.getElementById( 'bf-load-more' );
	var allPills     = Array.from( wrap.querySelectorAll( '.pf__pill' ) );

	// Active filter state — camelCase keys match the data-axis attributes.
	var state = {
		category : new Set(),
		tag      : new Set(),
	};

	// AJAX param names sent to the server.
	var axisParams = {
		category : 'category',
		tag      : 'tag',
	};

	// Maps JS state keys to property names in bfData.posts entries.
	// Both axes are arrays (a post can have multiple categories and tags).
	var axisDataProp = {
		category : 'cats',
		tag      : 'tags',
	};

	var currentPage     = 1;
	var isLoading       = false;
	var hasMore         = !! bfData.hasMore;
	var searchQuery     = '';
	var searchTimer     = null;
	var searchInput     = document.getElementById( 'bf-search' );
	var stickySearchInput = null;

	// ── Cascading pill availability ───────────────────────────────────────────────

	function updatePillAvailability() {
		var posts = bfData.posts;
		if ( ! posts || ! posts.length ) return;

		allPills.forEach( function ( pill ) {
			var axis  = pill.dataset.axis;
			var value = pill.dataset.value;

			// "All" pill and currently-selected pills are always visible.
			if ( value === '' || state[ axis ].has( value ) ) {
				pill.style.display = '';
				return;
			}

			// Show pill only if at least one post matches this value
			// combined with the active selections on all other axes.
			var hasMatch = posts.some( function ( p ) {
				var prop = axisDataProp[ axis ];
				if ( ! p[ prop ] ) return false;

				var matchesAxis = p[ prop ].indexOf( value ) !== -1;
				if ( ! matchesAxis ) return false;

				return Object.keys( state ).every( function ( a ) {
					if ( a === axis ) return true;
					if ( state[ a ].size === 0 ) return true;
					var aProp = axisDataProp[ a ];
					return Array.from( state[ a ] ).some( function ( v ) {
						return p[ aProp ] && p[ aProp ].indexOf( v ) !== -1;
					} );
				} );
			} );

			pill.style.display = hasMatch ? '' : 'none';
		} );
	}

	// ── AJAX fetch ────────────────────────────────────────────────────────────────

	function fetchPosts( page, replace, _alreadyVisible ) {
		if ( isLoading ) return;
		isLoading = true;
		setLoadingState( true );

		var params = new URLSearchParams();
		params.set( 'action',   'ninjatheme_blog_filter' );
		params.set( 'nonce',    bfData.nonce );
		params.set( 'page',     String( page ) );
		params.set( 'per_page', String( bfData.perPage ) );
		params.set( 'orderby',  bfData.orderby );
		params.set( 'order',    bfData.order );

		if ( searchQuery ) {
			params.set( 'search', searchQuery );
		}

		Object.keys( state ).forEach( function ( axis ) {
			var param = axisParams[ axis ];
			state[ axis ].forEach( function ( val ) {
				params.append( param + '[]', val );
			} );
		} );

		fetch( bfData.ajaxUrl, {
			method  : 'POST',
			headers : { 'Content-Type': 'application/x-www-form-urlencoded' },
			body    : params.toString(),
		} )
			.then( function ( r ) { return r.json(); } )
			.then( function ( data ) {
				if ( ! data.success ) {
					isLoading = false;
					setLoadingState( false );
					return;
				}

				var d = data.data;

				var tmp = document.createElement( 'div' );
				tmp.innerHTML = d.html;
				var batchCount = tmp.children.length;
				var nowVisible = ( _alreadyVisible || 0 ) + batchCount;

				if ( replace ) {
					replaceGrid( d.html );
				} else {
					appendToGrid( d.html );
				}

				currentPage = page;
				updateMeta( d.has_more, d.total );
				isLoading = false;
				setLoadingState( false );

				if ( d.has_more && nowVisible < bfData.perPage ) {
					fetchPosts( page + 1, false, nowVisible );
				}
			} )
			.catch( function () {
				isLoading = false;
				setLoadingState( false );
			} );
	}

	// ── Grid update ───────────────────────────────────────────────────────────────

	function replaceGrid( html ) {
		var existing = Array.from( grid.children );

		existing.forEach( function ( card ) {
			card.style.transition = 'opacity 0.18s ease, transform 0.18s ease';
			card.style.opacity    = '0';
			card.style.transform  = 'scale(0.97)';
		} );

		setTimeout( function () {
			grid.innerHTML = html;
			entranceAnimation( Array.from( grid.children ) );
		}, 200 );
	}

	function appendToGrid( html ) {
		var temp = document.createElement( 'div' );
		temp.innerHTML = html;
		var newCards = Array.from( temp.children );

		newCards.forEach( function ( card ) {
			grid.appendChild( card );
		} );

		entranceAnimation( newCards );
	}

	function entranceAnimation( cards ) {
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

	// ── Meta bar ──────────────────────────────────────────────────────────────────

	function updateMeta( newHasMore, total ) {
		hasMore = !! newHasMore;

		if ( loadMoreWrap ) {
			loadMoreWrap.hidden        = ! hasMore;
			loadMoreWrap.style.display = hasMore ? '' : 'none';
		}

		if ( emptyMsg ) {
			emptyMsg.hidden = total > 0;
		}

		if ( countEl ) {
			var showing = grid.children.length;
			if ( total === 0 ) {
				countEl.textContent = '';
			} else if ( hasMore ) {
				countEl.textContent = 'Showing ' + showing + ' of ' + total + ' articles';
			} else {
				var activeFilters = Object.values( state ).some( function ( s ) { return s.size > 0; } );
				countEl.textContent = activeFilters
					? total + ' article' + ( total !== 1 ? 's' : '' ) + ' match your filters'
					: '';
			}
		}
	}

	function setLoadingState( loading ) {
		if ( ! loadMoreBtn ) return;
		loadMoreBtn.disabled = loading;
		loadMoreBtn.classList.toggle( 'is-loading', loading );
	}

	// ── Active filter chips ───────────────────────────────────────────────────────

	var axisLabels = {
		category : 'Category',
		tag      : 'Topic',
	};

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

				( function ( a, v ) {
					btn.addEventListener( 'click', function () {
						state[ a ].delete( v );
						syncPillState( a );
						renderChips();
						fetchPosts( 1, true );
					} );
				} )( axis, val );

				chip.appendChild( label );
				chip.appendChild( btn );
				chipsWrap.appendChild( chip );
			} );
		} );

		if ( hasActive ) {
			var clearBtn = document.createElement( 'button' );
			clearBtn.type      = 'button';
			clearBtn.className = 'pf__clear-all';
			clearBtn.textContent = 'Clear all';
			clearBtn.addEventListener( 'click', clearAll );
			chipsWrap.appendChild( clearBtn );
		}

		chipsWrap.hidden = ! hasActive;
		syncAllStickyDropdowns();
		saveFilterState();
		updatePillAvailability();
	}

	// ── Pill sync ─────────────────────────────────────────────────────────────────

	function syncPillState( axis ) {
		allPills
			.filter( function ( p ) { return p.dataset.axis === axis; } )
			.forEach( function ( p ) {
				var val      = p.dataset.value;
				var isAll    = val === '';
				var isActive = isAll ? state[ axis ].size === 0 : state[ axis ].has( val );
				p.classList.toggle( 'pf__pill--active', isActive );
				p.setAttribute( 'aria-pressed', isActive ? 'true' : 'false' );
			} );
	}

	function clearAll() {
		Object.keys( state ).forEach( function ( axis ) {
			state[ axis ].clear();
			syncPillState( axis );
		} );
		searchQuery = '';
		if ( searchInput ) searchInput.value = '';
		if ( stickySearchInput ) stickySearchInput.value = '';
		renderChips();
		fetchPosts( 1, true );
	}

	// ── Pill click handler ────────────────────────────────────────────────────────

	allPills.forEach( function ( pill ) {
		pill.addEventListener( 'click', function () {
			var axis  = pill.dataset.axis;
			var value = pill.dataset.value;

			if ( value === '' ) {
				state[ axis ].clear();
			} else {
				if ( state[ axis ].has( value ) ) {
					state[ axis ].delete( value );
				} else {
					state[ axis ].add( value );
				}
			}

			syncPillState( axis );
			renderChips();
			fetchPosts( 1, true );
		} );
	} );

	// ── Load More ─────────────────────────────────────────────────────────────────

	if ( loadMoreBtn ) {
		loadMoreBtn.addEventListener( 'click', function () {
			if ( ! hasMore ) {
				updateMeta( false, 0 );
				return;
			}
			fetchPosts( currentPage + 1, false );
		} );
	}

	// ── Sticky filter bar ─────────────────────────────────────────────────────────

	function initStickyBar() {
		var stickyBar   = document.getElementById( 'bf-sticky-bar' );
		var axesPanel   = document.getElementById( 'bf-axes-panel' );
		var stickyClear = document.getElementById( 'bf-sticky-clear' );

		if ( ! stickyBar || ! axesPanel ) return;

		function updateStickyTop() {
			var header = document.querySelector( '.site-header' );
			var offset = 0;
			if ( header ) {
				var pos = window.getComputedStyle( header ).position;
				if ( pos === 'fixed' || header.classList.contains( 'scrolled' ) ) {
					offset = header.offsetHeight;
				}
			}
			stickyBar.style.top = offset + 'px';
		}

		updateStickyTop();
		window.addEventListener( 'resize', updateStickyTop, { passive: true } );

		if ( typeof IntersectionObserver !== 'undefined' ) {
			var observer = new IntersectionObserver(
				function ( entries ) {
					var isHidden = ! entries[ 0 ].isIntersecting;
					stickyBar.classList.toggle( 'is-visible', isHidden );
					if ( isHidden ) updateStickyTop();
				},
				{ threshold: 0 }
			);
			observer.observe( axesPanel );
		}

		if ( stickyClear ) {
			stickyClear.addEventListener( 'click', clearAll );
		}

		stickySearchInput = stickyBar ? stickyBar.querySelector( '.pf__sticky-search input' ) : null;

		if ( stickySearchInput ) {
			stickySearchInput.addEventListener( 'input', function () {
				if ( searchInput ) searchInput.value = stickySearchInput.value;
				clearTimeout( searchTimer );
				searchTimer = setTimeout( function () {
					searchQuery = stickySearchInput.value.trim();
					fetchPosts( 1, true );
				}, 350 );
			} );
		}

		initStickyDropdowns();
	}

	// ── Sticky dropdown logic ─────────────────────────────────────────────────────

	function initStickyDropdowns() {
		var stickyBar = document.getElementById( 'bf-sticky-bar' );
		if ( ! stickyBar ) return;

		var dropdowns = Array.from( stickyBar.querySelectorAll( '.pf__sd' ) );

		dropdowns.forEach( function ( sd ) {
			var axis    = sd.dataset.axis;
			var trigger = sd.querySelector( '.pf__sd-trigger' );
			var panel   = sd.querySelector( '.pf__sd-panel' );
			var list    = sd.querySelector( '.pf__sd-list' );

			if ( ! trigger || ! panel || ! list ) return;

			trigger.addEventListener( 'click', function ( e ) {
				e.stopPropagation();
				var isOpen = ! panel.hidden;

				dropdowns.forEach( function ( other ) {
					var p = other.querySelector( '.pf__sd-panel' );
					if ( p ) p.hidden = true;
					other.classList.remove( 'is-open' );
				} );

				panel.hidden = isOpen;
				sd.classList.toggle( 'is-open', ! isOpen );
			} );

			list.addEventListener( 'click', function ( e ) {
				var item = e.target.closest( '.pf__sd-item' );
				if ( ! item ) return;

				var val = item.dataset.value;
				if ( state[ axis ].has( val ) ) {
					state[ axis ].delete( val );
				} else {
					state[ axis ].add( val );
				}

				syncPillState( axis );
				syncStickyDropdown( sd, axis );
				renderChips();
				fetchPosts( 1, true );
			} );

			panel.addEventListener( 'click', function ( e ) { e.stopPropagation(); } );
		} );

		document.addEventListener( 'click', function () {
			dropdowns.forEach( function ( sd ) {
				var p = sd.querySelector( '.pf__sd-panel' );
				if ( p ) p.hidden = true;
				sd.classList.remove( 'is-open' );
			} );
		} );
	}

	function syncStickyDropdown( sd, axis ) {
		var count = sd.querySelector( '.pf__sd-count' );
		var list  = sd.querySelector( '.pf__sd-list' );

		if ( ! list ) return;

		var activeVals = state[ axis ];
		var n = activeVals.size;

		if ( count ) {
			count.hidden      = n === 0;
			count.textContent = n > 0 ? String( n ) : '';
		}

		sd.classList.toggle( 'has-active', n > 0 );

		var items = Array.from( list.querySelectorAll( '.pf__sd-item' ) );

		items.forEach( function ( item ) {
			var isSelected = activeVals.has( item.dataset.value );
			item.setAttribute( 'aria-selected', isSelected ? 'true' : 'false' );
			item.classList.toggle( 'is-selected', isSelected );
		} );

		var selected   = items.filter( function ( i ) { return activeVals.has( i.dataset.value ); } );
		var unselected = items.filter( function ( i ) { return ! activeVals.has( i.dataset.value ); } );
		selected.concat( unselected ).forEach( function ( item ) { list.appendChild( item ); } );
	}

	function syncAllStickyDropdowns() {
		var stickyBar   = document.getElementById( 'bf-sticky-bar' );
		var stickyClear = document.getElementById( 'bf-sticky-clear' );

		if ( ! stickyBar ) return;

		var hasActive = Object.values( state ).some( function ( s ) { return s.size > 0; } );
		if ( stickyClear ) stickyClear.hidden = ! hasActive;

		Array.from( stickyBar.querySelectorAll( '.pf__sd' ) ).forEach( function ( sd ) {
			var axis = sd.dataset.axis;
			if ( axis && state[ axis ] ) syncStickyDropdown( sd, axis );
		} );
	}

	// ── Arrow navigation ──────────────────────────────────────────────────────────

	function initArrows() {
		var SCROLL_AMOUNT = 220;
		var tracks = Array.from( wrap.querySelectorAll( '.pf__axis-track' ) );

		tracks.forEach( function ( track ) {
			var prevBtn   = track.querySelector( '.pf__arrow--prev' );
			var nextBtn   = track.querySelector( '.pf__arrow--next' );
			var pillsWrap = track.querySelector( '.pf__pills-wrap' );

			if ( ! prevBtn || ! nextBtn || ! pillsWrap ) return;

			function updateArrows() {
				var scrollLeft  = Math.round( pillsWrap.scrollLeft );
				var maxScroll   = Math.round( pillsWrap.scrollWidth - pillsWrap.clientWidth );
				var hasOverflow = maxScroll > 4;

				var showPrev = hasOverflow && scrollLeft > 4;
				var showNext = hasOverflow && scrollLeft < maxScroll - 4;

				prevBtn.classList.toggle( 'is-visible', showPrev );
				nextBtn.classList.toggle( 'is-visible', showNext );

				pillsWrap.classList.remove( 'pf--mask-right', 'pf--mask-both', 'pf--mask-left' );
				if ( showPrev && showNext ) {
					pillsWrap.classList.add( 'pf--mask-both' );
				} else if ( showNext ) {
					pillsWrap.classList.add( 'pf--mask-right' );
				} else if ( showPrev ) {
					pillsWrap.classList.add( 'pf--mask-left' );
				}
			}

			prevBtn.addEventListener( 'click', function () {
				pillsWrap.scrollBy( { left: -SCROLL_AMOUNT, behavior: 'smooth' } );
			} );

			nextBtn.addEventListener( 'click', function () {
				pillsWrap.scrollBy( { left: SCROLL_AMOUNT, behavior: 'smooth' } );
			} );

			pillsWrap.addEventListener( 'scroll', updateArrows, { passive: true } );

			if ( typeof ResizeObserver !== 'undefined' ) {
				new ResizeObserver( updateArrows ).observe( pillsWrap );
			}

			updateArrows();
		} );
	}

	// ── Session persistence ───────────────────────────────────────────────────────

	function saveFilterState() {
		var hasActive = Object.values( state ).some( function ( s ) { return s.size > 0; } );
		if ( hasActive ) {
			var saved = {};
			Object.keys( state ).forEach( function ( axis ) {
				if ( state[ axis ].size > 0 ) {
					saved[ axis ] = Array.from( state[ axis ] );
				}
			} );
			sessionStorage.setItem( 'bf_filter_state', JSON.stringify( saved ) );
		} else {
			sessionStorage.removeItem( 'bf_filter_state' );
		}
	}

	function restoreFilterState() {
		try {
			var raw = sessionStorage.getItem( 'bf_filter_state' );
			if ( ! raw ) return false;

			var saved = JSON.parse( raw );
			var hasActive = false;

			Object.keys( saved ).forEach( function ( axis ) {
				if ( state[ axis ] && Array.isArray( saved[ axis ] ) ) {
					saved[ axis ].forEach( function ( val ) {
						state[ axis ].add( val );
						hasActive = true;
					} );
				}
			} );

			if ( hasActive ) {
				Object.keys( state ).forEach( function ( axis ) { syncPillState( axis ); } );
				renderChips();
				fetchPosts( 1, true );
				return true;
			}
		} catch ( e ) { /* ignore */ }
		return false;
	}

	// ── Search input ──────────────────────────────────────────────────────────────

	if ( searchInput ) {
		searchInput.addEventListener( 'input', function () {
			if ( stickySearchInput ) stickySearchInput.value = searchInput.value;
			clearTimeout( searchTimer );
			searchTimer = setTimeout( function () {
				searchQuery = searchInput.value.trim();
				fetchPosts( 1, true );
			}, 350 );
		} );
	}

	// ── Init ──────────────────────────────────────────────────────────────────────

	initArrows();
	initStickyBar();

	var hasDefaults = (
		( bfData.defaultCategory && bfData.defaultCategory.length ) ||
		( bfData.defaultTag && bfData.defaultTag.length )
	);

	if ( hasDefaults ) {
		if ( bfData.defaultCategory ) {
			bfData.defaultCategory.forEach( function ( cat ) { state.category.add( cat ); } );
		}
		if ( bfData.defaultTag ) {
			bfData.defaultTag.forEach( function ( tag ) { state.tag.add( tag ); } );
		}
		Object.keys( state ).forEach( function ( axis ) { syncPillState( axis ); } );
		renderChips();
		updateMeta( bfData.hasMore, bfData.total );
		updatePillAvailability();
	} else if ( ! restoreFilterState() ) {
		updateMeta( bfData.hasMore, bfData.total );
		updatePillAvailability();
	}

} )();
