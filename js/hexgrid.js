// hex-grid element
var hexGridProto = Object.create(HTMLDivElement.prototype);
hexGridProto.getTile = function(x, y) {
	return $("hex-row:eq(" + y + ") hex-tile:eq(" + x + ")", this)[0];
};
var hexGrid = document.registerElement("hex-grid", {
	prototype: hexGridProto
});

// hex-row element
var hexRow = document.registerElement("hex-row", {
	prototype: Object.create(HTMLDivElement.prototype)
});

// hex-tile element
var hexTileProto = Object.create(HTMLDivElement.prototype);

hexTileProto.getPosition = function() {
	var row = $(this).parent();
	return [$(this).index(), row.index()];
};

var hexTile = document.registerElement("hex-tile", {
	prototype: hexTileProto
});

function getPosition2(tile) {
	var row = tile.parent();
	return [tile.index(), row.index()];
}
