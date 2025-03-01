const cards = import.meta.glob('./Shared/Cards/*.vue', {eager: true});

const registeredCards = {};

Object.entries(cards).forEach(([path, card]) => {
    const segments = path.split('/');
    if (segments.length === 4) {
        let name = `${segments[3].replace('.vue', '')}`
        name = name.replace(/([a-z0-9])([A-Z])/g, '$1-$2')
            .replace(/([A-Z]+)([A-Z][a-z])/g, '$1-$2')
            .toLowerCase();

        registeredCards[name] = card.default || card;
    }
});

export default registeredCards;
