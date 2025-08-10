#!/usr/bin/env node

// Import the letter checking logic
const { shouldMarkAsWrongLocation, evaluateGuess } = require('./letter-logic.js')

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

function runTests() {
    console.log('🎯 Wordle Letter Check Tests\n')
    
    let passCount = 0
    
    testCases.forEach((testCase, index) => {
        const result = evaluateGuess(testCase.guess, testCase.target)
        const passed = JSON.stringify(result) === JSON.stringify(testCase.expected)
        
        if (passed) passCount++
        
        console.log(`${index + 1}. ${testCase.name}`)
        console.log(`   Target: ${testCase.target.toUpperCase()}`)
        console.log(`   Guess:  ${testCase.guess.toUpperCase()}`)
        console.log(`   Expected: ${JSON.stringify(testCase.expected)}`)
        console.log(`   Got:      ${JSON.stringify(result)}`)
        console.log(`   Result:   ${passed ? '✅ PASS' : '❌ FAIL'}\n`)
    })
    
    console.log(`📊 Test Summary: ${passCount}/${testCases.length} tests passed`)
    
    if (passCount === testCases.length) {
        console.log('🎉 All tests passed!')
        process.exit(0)
    } else {
        console.log('💥 Some tests failed!')
        process.exit(1)
    }
}

runTests()