export function getFirstWordOfName(name) {
  const alphabet = 'abcdefghijklmnopqrstuvwxyz'.split('');
  let counter = null;
  let firstWordInName = '';
  for (const [index, letter] of Object.entries(name)) {
    for (const alphabetLetter of alphabet) {
      if (letter === alphabetLetter.toUpperCase() && counter !== 2) {
        counter = 2;
      } else if (letter === alphabetLetter.toUpperCase() && counter === 2) {
        firstWordInName = name.slice(0, index).toLowerCase();
        break;
      }
    };
  };
  return firstWordInName;
}
