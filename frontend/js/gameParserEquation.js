let fruitsAndVegetables = [
    "\u{1F34F}", // Green Apple
    "\u{1F34E}", // Red Apple
    "\u{1F350}", // Pear
    "\u{1F34A}", // Tangerine
    "\u{1F34B}", // Lemon
    "\u{1F34C}", // Banana
    "\u{1F349}", // Watermelon
    "\u{1F347}", // Grapes
    "\u{1F353}", // Strawberry
    "\u{1F348}", // Melon
    "\u{1F352}", // Cherries
    "\u{1F351}", // Peach
    "\u{1F96D}", // Mango
    "\u{1F34D}", // Pineapple
    "\u{1F965}", // Coconut
    "\u{1F95D}", // Kiwi Fruit
    "\u{1F345}", // Tomato
    "\u{1F346}", // Eggplant
    "\u{1F951}", // Avocado
    "\u{1F966}", // Broccoli
    "\u{1F96C}", // Leafy Green
    "\u{1F952}", // Cucumber
    "\u{1F336}", // Hot Pepper
    "\u{1F33D}", // Ear of Corn
    "\u{1F955}", // Carrot
    "\u{1F9C5}", // Onion
    "\u{1F9C4}", // Garlic
    "\u{1F954}" // Potato
];

export function parseEquations(equationsString) {
    // shuffles the fruits and vegetables
    fruitsAndVegetables = shuffleArray(fruitsAndVegetables);

    // Split the string into individual equations
    const equations = equationsString.split(";");

    // Create an object to store the unknowns and their assigned fruit characters
    const unknowns = {};
    //console.log(equations);
    // Iterate over each equation
    let counter = 0;
    equations.forEach((equation, index) => {
        // Remove whitespace and split the equation into parts
        const parts = equation.trim().split(/\s*=\s*/);
        // Check if there are unknowns and coefficients
        if (parts.length === 2) {
            const [expression, result] = parts;

            // Extract unknowns from the expression
            const unknownsInExpression = expression.match(/[a-zA-Z]+/g);

            if (unknownsInExpression) {
                // Assign fruit characters to unknowns
                unknownsInExpression.forEach((unknown) => {
                    if (!unknowns[unknown]) {
                        unknowns[unknown] = fruitsAndVegetables[counter++];
                    }
                });
            }
        }
    });

    // Return the unknowns object if needed
    return unknowns;
}

function shuffleArray(array) {
    for (let i = array.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        [array[i], array[j]] = [array[j], array[i]];
    }
    return array;
}