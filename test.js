#!/usr/bin/env node

// Import the letter checking logic
const { evaluateGuess } = require('./letter-logic.js')

const testCases = require('./test-cases.js')

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