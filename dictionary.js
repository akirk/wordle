(function () {
	const form = document.querySelector('[data-dictionary-controls]');
	const list = document.querySelector('[data-word-list]');
	const count = document.querySelector('[data-result-count]');

	if (!form || !list || !count || !window.wordleDictionaryData) {
		return;
	}

	const onlyLetters = (value) => value.toLowerCase().replace(/[^a-z]/g, '');
	const normalizePattern = (value) => value.toLowerCase().replace(/[^a-z.?]/g, '').slice(0, 5);

	function getFilters() {
		const data = new FormData(form);

		return {
			pattern: normalizePattern(data.get('pattern') || ''),
			include: new Set(onlyLetters(data.get('include') || '').split('')),
			exclude: new Set(onlyLetters(data.get('exclude') || '').split('')),
			source: data.get('source') === 'valid' ? 'valid' : 'answers',
		};
	}

	function matchesPattern(word, pattern) {
		if (!pattern) {
			return true;
		}

		for (let index = 0; index < pattern.length; index += 1) {
			const expected = pattern[index];

			if ((expected !== '.' && expected !== '?') && word[index] !== expected) {
				return false;
			}
		}

		return true;
	}

	function matchesFilters(word, filters) {
		if (!matchesPattern(word, filters.pattern)) {
			return false;
		}

		for (const letter of filters.include) {
			if (letter && !word.includes(letter)) {
				return false;
			}
		}

		for (const letter of filters.exclude) {
			if (letter && word.includes(letter)) {
				return false;
			}
		}

		return true;
	}

	function render() {
		const filters = getFilters();
		const words = window.wordleDictionaryData[filters.source] || [];
		const matches = words.filter((word) => matchesFilters(word, filters));

		count.textContent = matches.length.toLocaleString();
		list.replaceChildren(
			...matches.slice(0, 500).map((word) => {
				const item = document.createElement('button');
				item.type = 'button';
				item.className = 'word-chip';
				item.textContent = word;
				item.addEventListener('click', () => navigator.clipboard?.writeText(word));
				return item;
			})
		);

		if (matches.length > 500) {
			const overflow = document.createElement('p');
			overflow.className = 'result-note';
			overflow.textContent = `Showing 500 of ${matches.length.toLocaleString()} matches. Add filters to narrow the list.`;
			list.append(overflow);
		}
	}

	form.addEventListener('input', render);
	form.addEventListener('change', render);
	render();
}());
