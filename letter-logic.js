// Wordle letter checking logic
function shouldMarkAsWrongLocation(guess, letter, currentIndex, targetWord) {
  // Only check if letter exists in target word
  if (targetWord.indexOf(letter) === -1) {
    return false
  }
  
  // Count how many times the letter appears in the target word
  var targetLetterCount = 0
  for (var i = 0; i < targetWord.length; i++) {
    if (targetWord[i] === letter) {
      targetLetterCount++
    }
  }
  
  // Count how many correct positions (green) we have for this letter in the entire guess
  var correctPositions = 0
  for (var i = 0; i < guess.length; i++) {
    if (guess[i] === letter && targetWord[i] === letter) {
      correctPositions++
    }
  }
  
  // Count wrong-location marks (yellow) that should come before this position
  var yellowCount = 0
  for (var i = 0; i < currentIndex; i++) {
    if (guess[i] === letter && targetWord[i] !== letter) {
      // This earlier position should get yellow if we have capacity
      if (correctPositions + yellowCount < targetLetterCount) {
        yellowCount++
      }
    }
  }
  
  // This position gets yellow if we still have capacity after accounting for 
  // all greens and earlier yellows
  return correctPositions + yellowCount < targetLetterCount
}

function evaluateGuess(guess, targetWord) {
  var result = []
  for (var i = 0; i < guess.length; i++) {
    var letter = guess[i]
    if (targetWord[i] === letter) {
      result.push('correct')
    } else if (shouldMarkAsWrongLocation(guess, letter, i, targetWord)) {
      result.push('wrong-location')
    } else {
      result.push('wrong')
    }
  }
  return result
}

// Export for Node.js if available
if (typeof module !== 'undefined' && module.exports) {
  module.exports = { shouldMarkAsWrongLocation, evaluateGuess }
}