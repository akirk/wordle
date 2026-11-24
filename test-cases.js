// Test cases
const testCases = [
    {
        name: "Bug scenario: minty vs jetty",
        target: "minty",
        guess: "jetty",
        expected: ["wrong", "wrong", "wrong", "correct", "correct"]
    },
    {
        name: "Simple correct word",
        target: "hello",
        guess: "hello",
        expected: ["correct", "correct", "correct", "correct", "correct"]
    },
    {
        name: "No matches",
        target: "hello",
        guess: "party",
        expected: ["wrong", "wrong", "wrong", "wrong", "wrong"]
    },
    {
        name: "Double letter in guess, single in target",
        target: "hello",
        guess: "llama",
        expected: ["wrong-location", "wrong-location", "wrong", "wrong", "wrong"]
    },
    {
        name: "Double letter in target, single in guess",
        target: "llama",
        guess: "hello",
        expected: ["wrong", "wrong", "wrong-location", "wrong-location", "wrong"]
    },
    {
        name: "Multiple same letters - complex case",
        target: "alley",
        guess: "lllll",
        expected: ["wrong", "correct", "correct", "wrong", "wrong"]
    }
]


// Shared by the Node runner and the browser test page.
if (typeof module !== 'undefined' && module.exports) {
    module.exports = testCases
}
