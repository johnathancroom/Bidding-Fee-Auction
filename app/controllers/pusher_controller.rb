class PusherController < ApplicationController
  protect_from_forgery :except => :auth # No CSRF

  def auth
      response = Pusher[params[:channel_name]].authenticate(params[:socket_id], {
        :user_id => (@current_user.id if logged_in?) || 1000*rand(),
        :user_info => {
          :name => (@current_user.username if logged_in?) || "Guest"
        }
      })
      render :json => response
  end
end