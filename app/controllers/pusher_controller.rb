require 'pusher'

class PusherController < ApplicationController
  protect_from_forgery :except => :auth # No CSRF
  before_filter :init

  def auth
      response = Pusher[params[:channel_name]].authenticate(params[:socket_id], {
        :user_id => @current_user.id || "",
        :user_info => {
          :name => @current_user.username
        }
      })
      render :json => response
  end
  
  def init
    Pusher.app_id = '16968'
    Pusher.key = '7da53c26a313d349592f'
    Pusher.secret = 'cc70ff24049d85b28d07'
  end
end