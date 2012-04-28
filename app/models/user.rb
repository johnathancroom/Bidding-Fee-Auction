require 'bcrypt'

class User < ActiveRecord::Base
  include BCrypt
  
  attr_accessible :bids, :id, :username, :password
  has_many :auctions
  
  def password
    @password ||= Password.new(password_hash)
  end
  
  def password=(new_password)
    @password = Password.create(new_password)
    self.password_hash = @password
  end
end
