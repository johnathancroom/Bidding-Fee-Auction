/////////////////////////////////////////////////////////
//
//  Pusher
//
/////////////////////////////////////////////////////////
Pusher.log = function(message) {
  if(window.console && window.console.log) window.console.log(message);
};

var auctionSocket = {
  options: {
    key: '429ab7f0784ed502885d',
    channel: 'auctions',
    channelType: 'presence-',
    eventType: 'client-'
  },
  pusher: null,
  subscription: null,
  
  init: function() {
    this.pusher = new Pusher(this.options.key);
    this.subscription = this.pusher.subscribe(this.options.channelType + this.options.channel);
  },
  
  send: function(event, message) {
    return this.subscription.trigger(this.options.eventType + event, message);
  },
  
  listen: function(event, callback) {
    this.subscription.bind(this.options.eventType + event, callback);
  }
};

var charles = auctionSocket;
charles.init();