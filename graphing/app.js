class Canvas {

    constructor(element, width, height) {
        this.element = element;
        element.width = width;
        element.height = height;

        this.settings = {
            gridEnabled: false,
            zoom: 100,
            x: 0,
            y: 0
        }

        this.graphs = [];

        element.addEventListener("mousemove", evt => {
            let x = evt.pageX - evt.target.offsetLeft;
            let y = evt.pageY - evt.target.offsetTop;

            let ctx = element.getContext("2d");

            this.draw();
            ctx.beginPath();
            ctx.moveTo(x, 0);
            ctx.lineTo(x, element.height);
            ctx.strokeStyle = "red";
            ctx.stroke();
            ctx.closePath();

            this.graphs.forEach(graph => {
                let y = graph((x - element.width/2) / this.settings.zoom);
                let coordinates = this.transform(0, y);
                ctx.font = "10px Arial";
                ctx.beginPath();
                ctx.arc(x, coordinates.y, 3, 0, 2 * Math.PI);
                ctx.closePath();
                ctx.fillStyle = "red";
                ctx.fill();
                ctx.fillStyle = "black";
                ctx.textAlign = "left";
                ctx.fillText(y.toString().slice(0, y.toString().indexOf(".") + 5), x + 6, coordinates.y + 4);
            });
        });
    }


    setGridEnabled(state) {
        this.settings.gridEnabled = state;
        this.draw();
    }

    zoom(zoom) {
        this.settings.zoom = zoom;
        this.draw();
    }

    draw() {
        const ctx = this.element.getContext("2d");
        ctx.clearRect(0, 0, this.element.width, this.element.height);

        // draw graph
        this.graphs.forEach(graph => {
            ctx.strokeStyle = "blue";
            ctx.beginPath();
            ctx.moveTo(0, 0);


            for(let i = 0; i < this.element.width; i++) {
                let x = (-this.element.width/2 + i) / this.settings.zoom;
                let coordinates = this.transform(x, graph(x));
                ctx.lineTo(coordinates.x, coordinates.y);
            }

            ctx.stroke();
            ctx.closePath();
        });

        // draw x-axis

        ctx.beginPath();
        ctx.moveTo(0, this.element.height / 2);
        ctx.lineTo(this.element.width, this.element.height / 2);
        ctx.strokeStyle = "grey";
        ctx.stroke();
        ctx.closePath();

        // draw y-axis

        ctx.beginPath();
        ctx.moveTo(this.element.width / 2, 0);
        ctx.lineTo(this.element.width / 2, this.element.height);
        ctx.strokeStyle = "grey";
        ctx.stroke();
        ctx.closePath();

        // draw grid
        if(this.settings.gridEnabled === true) {
            ctx.font = "10px Arial";
            ctx.textAlign = "center";

            let step = 0.5;

            for(let i = -this.element.width / 2; i < this.element.width / 2; i += step) {
                let coordinates = this.transform(i, 0);
                ctx.fillText(i, coordinates.x, coordinates.y + 10);
                ctx.beginPath();
                ctx.moveTo(coordinates.x, coordinates.y - 2);
                ctx.lineTo(coordinates.x, coordinates.y + 2);
                ctx.strokeStyle = "grey";
                ctx.stroke();
                ctx.closePath();
            }

            for(let i = -this.element.height / 2; i < this.element.height / 2; i += step) {
                let coordinates = this.transform(0, i);
                ctx.fillText(i, coordinates.x - 10, coordinates.y);
                ctx.beginPath();
                ctx.moveTo(coordinates.x - 2, coordinates.y);
                ctx.lineTo(coordinates.x + 2, coordinates.y);
                ctx.strokeStyle = "grey";
                ctx.stroke();
                ctx.closePath();
            }
        }
    }

    transform(x, y) {
        return {
            x: (this.element.width / 2) + x * this.settings.zoom,
            y: (this.element.height / 2) - y * this.settings.zoom
        };
    }

    addGraph(graph) {
        this.graphs.push(graph);
        this.draw();
    }
}