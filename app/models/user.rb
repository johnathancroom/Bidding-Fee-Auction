class User < ActiveRecord::Base
  attr_accessible :bids, :id, :username
  has_many :auctions
end
