class RenameBidderIdToUserId < ActiveRecord::Migration
  def change
    rename_column :auctions, :bidder_id, :user_id
  end
end
