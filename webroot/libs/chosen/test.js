var choose = require("./main.js").choose;

console.log("Who do you choose?");

choose(
    ["Bulbasaur", "Pikachu", "Torchic", "Lucario", "Raichu"],
    function(x) {console.log("I choose you, "+x+"!");},
    {style:"indent"}
);  
