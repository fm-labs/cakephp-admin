var styles = {
    ">": {
        normal: function(s) {
            return "  " + s;
        },
        chosen: function(s) {
            return "> " + s;
        }
    },
    "gray": {
        normal: function(s) {
            return "   \x1b[90m" + s + "\x1b[0m";
        },
        chosen: function(s) {
            return "   \x1b[37m" + s + "\x1b[0m";
        }
    },
    "indent": {
        normal: function(s) {
            return "  " + s;
        },
        chosen: function(s) {
            return "   " + s;
        }
    }
}



function choose(choices, cb, options) {
    var stdin  = (options?options.input: null) || process.stdin;
    var stdout = (options?options.output:null) || process.stdout;
    var chosen = 0;
    stdin.resume();
    stdin.setRawMode(true);

    function draw(choices, chosen) {
        var formatter = options.style ? (styles[options.style] || options.style) : styles[">"];
        choices.forEach(function(choice, index) {
            stdout.write(
                (index === chosen ? formatter.chosen : formatter.normal)(choice)
            );
            stdout.write("\n");
        });
    }
    function erase(choices) {
        for (var i=0; i<choices.length; i++) {
            stdout.write("\x1b[F\x1b[K");
        }
    }

    draw(choices, chosen);
    
    function done(val) {
        stdin.removeListener("data", ondata);
        stdin.setRawMode(false);
        stdin.pause();
        cb(val);
    }
    function ondata(d) {
        var key = d.toString();
        var exit = ["\003", "\x1b"];
        var enter = ["\x0d", "\x20"];
        var next = ["a", "l", "j", "\x1b[B", "\x1b[C"]; // not used, just documentation
        var prev = ["q", "p", "k", "\x1b[A", "\x1b[D"];

        if (exit.indexOf(key) !== -1) {
            done(null);
        } else {
            if (enter.indexOf(key) !== -1) {
                done(choices[chosen], chosen);
            } else {
                if (prev.indexOf(key) !== -1) {
                    chosen--;
                } else {
                    chosen++;
                }
                chosen = (chosen+choices.length*3) % choices.length; // yay js mod bug
                erase(choices);
                draw(choices, chosen);
            }
        }
    }

    stdin.on("data", ondata);
}

module.exports = {
    choose: choose
};
