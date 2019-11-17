
function Calculator(maxIterations) {
    this.evenCount = 0;
    this.unevenCount = 0;
    this.maxIterations = maxIterations;
    this.calculations = [];

    this.calculate = () => {
        this.calculations = [];
        this.evenCount = 0;
        this.unevenCount = 0;
        
        for(let i = 0; i < this.maxIterations; i++) {
            let j = 0;
            let diceNumber;

            do {
                diceNumber = 1 + Math.floor(Math.random() * 6);
                j++;
            } while(diceNumber != 6);

            if(j % 2 == 1) this.unevenCount++;
            else this.evenCount++;

            this.calculations.push({
                iteration: i,
                isEven: j % 2 == 0,
                average: this.evenCount / (this.unevenCount + this.evenCount)
            });
        }
    }

    this.getCalculations = () => {
        return this.calculations;
    }

    this.getMaxIteration = () => {
        return this.maxIterations;
    }

    this.setMaxIteration = (maxIterations) => {
        this.maxIterations = maxIterations;
    }

    this.getAverage = () => {
        let evenCount = 0;

        if(this.calculations.length < this.maxIterations) return 0;
        
        for(let i = 0; i < this.maxIterations; i++) {
            if(this.calculations[i].isEven) evenCount++;
        }

        return evenCount / this.maxIterations;
    }
}


function Simulation(canvas, maxIterations) {
    this.canvas = canvas;
    this.ctx = this.canvas.getContext("2d");
    this.lastFrame = 0;

    this.calculator = new Calculator(maxIterations);
    this.offset = {
        bottom: 0,
        top: 100,
        left: 0
    };

    this.loop = () => {
        let now = new Date().getTime();
        let interval = 1000 / 10;
        let elapsed = now - this.lastFrame;

        if(elapsed > interval) {
            this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);
            this.draw();

            this.lastFrame = now - (this.lastFrame > 0 ? (elapsed % interval) : 0);
        }

        requestAnimationFrame(this.loop);
    }

    this.calculateX = (x) => {
        return this.offset.left + x;
    }

    this.calculateY = (y) => {
        return this.canvas.height - this.offset.bottom - (this.canvas.height - this.offset.top - this.offset.bottom) * y;
    }

    this.calculateHeight = (height) => {
        return -(this.canvas.height - this.offset.top - this.offset.bottom) * height;
    }

    this.calculateWidth = () => {
        return (this.canvas.width - this.offset.left) / this.calculator.getMaxIteration();
    }

    this.draw = () => {
        let average = this.calculator.getAverage();

        this.ctx.font = "30px Arial";
        this.ctx.fillStyle = "black";
        if(!Number.isNaN(average)) this.ctx.fillText("Sum(P(A), k=1, n=" + this.calculator.getMaxIteration() + ") = " + average, 0, 30);

        this.calculator.getCalculations().forEach(calculation => {
            let x = calculation.iteration * this.calculateWidth();
            let y = calculation.average;

            this.ctx.fillStyle = "orange";
            this.ctx.fillRect(this.calculateX(x), this.calculateY(y), this.calculateWidth(), this.calculateHeight(1 - y));
            this.ctx.fillStyle = "lightblue";
            this.ctx.fillRect(this.calculateX(x), this.calculateY(0), this.calculateWidth(), this.calculateHeight(y));
        });
    }

    this.getCalculator = () => {
        return this.calculator;
    }

    this.start = () => {
        this.loop();
        this.calculator.calculate();
    }
}




let elements = {
    canvas: document.querySelector("canvas"),
    iterationCountLabel: document.querySelector("#iterationCountLabel"),
    iterationCountSlider: document.querySelector("#iterationCountSlider")
}

let simulation = new Simulation(elements.canvas, 50000);
simulation.start();
simulation.getCalculator().setMaxIteration(5000);

elements.iterationCountSlider.addEventListener("input", () => {
    let maxIterations = elements.iterationCountSlider.value;

    elements.iterationCountLabel.innerText = maxIterations;

    simulation.getCalculator().setMaxIteration(maxIterations);
});