const process = require('process');

const text = process.argv[2];
if(text === undefined){
    console.log('Please, define the input parameter');
}else {
    const vowels = text.match(/[aeiou]/gi).length;
    console.log(vowels);
}