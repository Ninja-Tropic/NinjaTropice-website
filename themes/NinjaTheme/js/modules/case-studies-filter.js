( function () {
	'use strict';

	var wrap         = document.getElementById( 'case-studies-filter-wrap' );
	if ( ! wrap || typeof csfData === 'undefined' ) return;

	var grid         = document.getElementById( 'csf-grid' );
	var emptyMsg     = document.getElementById( 'csf-empty' );
	var countEl      = document.getElementById( 'csf-count' );
	var loadMoreWrap = document.getElementById( 'csf-load-more-wrap' );
	var loadMoreBtn  = document.getElementById( 'csf-load-more' );

	var currentPage = 1;
	var isLoading   = false;

	// ── AJAX fetch ────────────────────────────────────────────────────────────────

	function fetchPosts( page, replace ) {
		if ( isLoading ) return;
		isLoading = true;
		setLoadingState( true );

		var params = new URLSearchParams();
		params.set( 'action',   'ninjatheme_case_studies_filter' );
		params.set( 'nonce',    csfData.nonce );
		params.set( 'page',     String( page ) );
		params.set( 'per_page', String( csfData.perPage ) );
		params.set( 'orderby',  csfData.orderby );
		params.set( 'order',    csfData.order );

		if ( csfData.defaultCategory && csfData.defaultCategory.length ) {
			csfData.defaultCategory.forEach( function ( cat ) {
				params.append( 'category[]', cat );
			} );
		}

		fetch( csfData.ajaxUrl, {
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

				if ( replace ) {
					replaceGrid( d.html );
				} else {
					appendToGrid( d.html );
				}

				currentPage = page;
				updateMeta( d.has_more, d.total );
				isLoading = false;
				setLoadingState( false );
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

	function updateMeta( hasMore, total ) {
		if ( loadMoreWrap ) {
			loadMoreWrap.hidden = ! hasMore;
		}

		if ( emptyMsg ) {
			emptyMsg.hidden = grid.children.length > 0;
		}

		if ( countEl ) {
			var showing = grid.children.length;
			if ( total === 0 ) {
				countEl.textContent = '';
			} else if ( hasMore ) {
				countEl.textContent = 'Showing ' + showing + ' of ' + total + ' case studies';
			} else {
				countEl.textContent = '';
			}
		}
	}

	function setLoadingState( loading ) {
		if ( ! loadMoreBtn ) return;
		loadMoreBtn.disabled = loading;
		loadMoreBtn.classList.toggle( 'is-loading', loading );
	}

	// ── Load More ─────────────────────────────────────────────────────────────────

	if ( loadMoreBtn ) {
		loadMoreBtn.addEventListener( 'click', function () {
			fetchPosts( currentPage + 1, false );
		} );
	}

	// ── Init ──────────────────────────────────────────────────────────────────────

	updateMeta( csfData.hasMore, csfData.total );

} )();
