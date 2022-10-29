var wsServer = 'ws://127.0.0.1:9502';
var websocket = new WebSocket(wsServer);
websocket.onopen = function (evt) {
    console.log("Connected to WebSocket server.");
    
    // send msg to server，对应到服务端的message事件
    websocket.send('Hello Server! i am client -- After Connected first time');
};

websocket.onclose = function (evt) {
    // 不会再发送，会报错：WebSocket is already in CLOSING or CLOSED state.
    // websocket.send('Hello Server! i am client -- After Close');

    console.log("Disconnected");
};

// 如果server端没有推送消息到客户端，则不会触发该事件
websocket.onmessage = function (evt) {
    console.log('Retrieved data from server: ' + evt.data);
    
    // 当连接close，也就是触发了close事件，此时还触发该请求「因为定时任务没有销毁」，会报错：
    // WebSocket is already in CLOSING or CLOSED state.
    // 然后，js脚本终止

    // setTimeout(() => {websocket.send('Hello Server! i am client')}, 1000)
    // 延迟一秒后执行，循环3次
    // for (let i = 0; i <= 3; i++) {
    //     // send msg to server
    //     setTimeout(() => {websocket.send('Hello Server! i am client')}, 1000)
    // }
};

websocket.onerror = function (evt, e) {
    console.log('Error occured: ' + evt.data);
};
